<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;
use App\Models\Store;
use Illuminate\Validation\Rule;
use App\Models\PosOrder;
use App\Models\PosOrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use App\Models\StoreProduct;
use App\Models\Setting;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PosUserController extends Controller
{
    protected $pos;
    protected $store;
    protected $product;
    protected $order;
    protected $orderdetails;

    public function __construct(){
        $this->pos = new Pos();
        $this->store = new Store();
        $this->product = new Product();
        $this->order   = new PosOrder();
        $this->orderdetails = new PosOrderDetail();
    }

    public function index(){
        $posuser = $this->pos
        ->with('store')
        ->where('role', 1)
        ->orderBy('id', 'desc')
        ->paginate(config('constants.pagination_limit'));
        return view('posuser.index', compact('posuser'));
    }

    public function add(){
        $store = $this->store->all();
        $roles = Role::where('status', 1)->orderBy('role_name')->get();
        return view('posuser.add', compact('store', 'roles'));
    }

    public function save(Request $request){
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:pos,mobile',
            'email'  => 'required|email|max:255|unique:pos,email',
            'store'  => 'required',
            'password' => 'required|string|min:6',
            // All employee-profile fields below are intentionally optional.
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'designation' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_mobile' => 'nullable|digits:10',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_ifsc_code' => 'nullable|string|max:50',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $pos = $this->pos;
        $pos->name = $request->name;
        $pos->mobile = $request->mobile;
        $pos->email = $request->email;
        $pos->store_id = $request->store;
        $pos->role = 1;
        $pos->staff_id = generateStaffId();
        $pos->password = Hash::make($request->password);
        $pos->date_of_joining = $request->date_of_joining;
        $pos->date_of_birth = $request->date_of_birth;
        $pos->gender = $request->gender;
        $pos->designation = $request->designation;
        $pos->salary = $request->salary;
        $pos->address = $request->address;
        $pos->emergency_contact_name = $request->emergency_contact_name;
        $pos->emergency_contact_mobile = $request->emergency_contact_mobile;
        $pos->bank_name = $request->bank_name;
        $pos->bank_account_number = $request->bank_account_number;
        $pos->bank_ifsc_code = $request->bank_ifsc_code;

        $save = $pos->save();

        if ($save) {
            $employeeDirectory = public_path('employees/' . $pos->staff_id);
            File::ensureDirectoryExists($employeeDirectory);

            $documents = [];
            foreach ($request->file('documents', []) as $document) {
                $filename = uniqid('document_', true) . '.' . $document->extension();
                $document->move($employeeDirectory, $filename);
                $documents[] = 'employees/' . $pos->staff_id . '/' . $filename;
            }

            if ($documents) {
                $pos->documents = $documents;
                $pos->save();
            }

            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id){
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $pos = $this->pos->find($id);

        if (!$pos) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $store = $this->store->all();
        $roles = Role::where('status', 1)->orderBy('role_name')->get();

        return view('posuser.edit', compact('pos', 'store', 'roles'));
    }

    public function view($id)
    {
        $pos = $this->pos->where('role', 1)->findOrFail($id);
        $staffs = $this->pos
            ->with('roleMaster')
            ->where('user_id', $pos->id)
            ->orderByDesc('id')
            ->paginate(config('constants.pagination_limit'));

        return view('posuser.staff-list', compact('pos', 'staffs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:pos,id',
            'name'   => 'required|string|max:255',

            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('pos', 'mobile')->ignore($request->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pos', 'email')->ignore($request->id),
            ],

            'store'  => 'required|exists:store,id',
            // Optional employee-profile fields.
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'designation' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_mobile' => 'nullable|digits:10',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_ifsc_code' => 'nullable|string|max:50',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $pos = $this->pos->find($request->id);

        if (!$pos) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $pos->name = $request->name;
        $pos->mobile = $request->mobile;
        $pos->email = $request->email;
        $pos->store_id = $request->store;
        // Keep the existing role when an administrator updates a staff member.
        $pos->date_of_joining = $request->date_of_joining;
        $pos->date_of_birth = $request->date_of_birth;
        $pos->gender = $request->gender;
        $pos->designation = $request->designation;
        $pos->salary = $request->salary;
        $pos->address = $request->address;
        $pos->emergency_contact_name = $request->emergency_contact_name;
        $pos->emergency_contact_mobile = $request->emergency_contact_mobile;
        $pos->bank_name = $request->bank_name;
        $pos->bank_account_number = $request->bank_account_number;
        $pos->bank_ifsc_code = $request->bank_ifsc_code;


        if ($pos->save()) {
            $employeeDirectory = public_path('employees/' . $pos->staff_id);
            File::ensureDirectoryExists($employeeDirectory);

            $documents = $pos->documents ?? [];
            foreach ($request->file('documents', []) as $document) {
                $filename = uniqid('document_', true) . '.' . $document->extension();
                $document->move($employeeDirectory, $filename);
                $documents[] = 'employees/' . $pos->staff_id . '/' . $filename;
            }

            if ($request->hasFile('documents')) {
                $pos->documents = $documents;
                $pos->save();
            }

            return redirect()->back()->with('success', 'Employee updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function offerLetter($id)
    {
        $pos = $this->pos->with('store')->findOrFail($id);
        $company = Setting::where('slug', 'web_name')->first();

        return view('posuser.offer-letter-print', compact('pos', 'company'));
    }

    public function orders(){
        $orders = PosOrder::with('details')
                ->orderBy('id', 'desc')
                ->paginate(config('constants.pagination_limit'));

        return view('posuser.orders', compact('orders'));
    }

    public function orderView($id){
        $order = PosOrder::with('details')
            ->where('id', $id)
            ->firstOrFail();


        return view(
            'posuser.order-bill',
            compact('order')
        );
    }

    public function storeOrder(){
        $orders = StoreOrder::query()
            ->with(['store', 'posUser'])
            ->withCount('items')
            ->latest('id')
            ->paginate(config('constants.pagination_limit'));

        return view('posuser.store_order', compact('orders'));
    }

    public function storeOrderView(StoreOrder $order)
    {
        $order->load(['items.product', 'store', 'posUser']);

        return view('posuser.store_order_view', compact('order'));
    }

    public function updateStoreOrderStatus(Request $request, StoreOrder $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:2'],
        ]);

        try {
            DB::transaction(function () use ($order, $validated) {
                $order = StoreOrder::query()
                    ->with('items')
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                // A delivered order must never be processed twice.
                if ((int) $order->status === 2) {
                    return;
                }

                foreach ($order->items as $item) {
                    $storeProduct = StoreProduct::query()
                        ->where('store_id', $order->store_id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if ($storeProduct) {
                        $storeProduct->increment('qty', $item->quantity);
                    } else {
                        StoreProduct::create([
                            'store_id' => $order->store_id,
                            'product_id' => $item->product_id,
                            'qty' => $item->quantity,
                        ]);
                    }

                }

                $order->update(['status' => $validated['status']]);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Store order could not be marked as delivered.');
        }

        return back()->with('success', 'Store order delivered and stock added to the store successfully.');
    }

    public function downloadStoreOrderInvoice(StoreOrder $order)
    {
        abort_unless((int) $order->status === 2, 404);

        $order->load(['items.product', 'store', 'posUser']);

        return response()
            ->view('pos.product.orders.bill', compact('order'))
            ->header('Content-Disposition', 'attachment; filename="' . $order->order_number . '.html"');
    }

    public function bulkDeliverStoreOrders(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['required', 'integer', 'distinct', 'exists:store_orders,id'],
        ]);

        try {
            $deliveredCount = DB::transaction(function () use ($validated) {
                $count = 0;
                foreach (collect($validated['order_ids'])->sort()->values() as $orderId) {
                    $order = StoreOrder::query()->with('items')->lockForUpdate()->findOrFail($orderId);

                    // Delivered rows are ignored, so stock can never be added twice.
                    if ((int) $order->status === 2) {
                        continue;
                    }

                    foreach ($order->items as $item) {
                        $storeProduct = StoreProduct::query()
                            ->where('store_id', $order->store_id)
                            ->where('product_id', $item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($storeProduct) {
                            $storeProduct->increment('qty', $item->quantity);
                        } else {
                            StoreProduct::create([
                                'store_id' => $order->store_id,
                                'product_id' => $item->product_id,
                                'qty' => $item->quantity,
                            ]);
                        }

                    }

                    $order->update(['status' => 2]);
                    $count++;
                }

                return $count;
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Selected store orders could not be delivered. No stock was changed.');
        }

        return back()->with('success', $deliveredCount . ' selected order(s) delivered and store stock updated successfully.');
    }
}
