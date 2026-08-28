<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transcation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\Status;
use App\Models\TruckOrder;
use App\Models\Supplier;
use Milon\Barcode\DNS1D;
use App\Models\OrderBarcode;
use App\Models\User;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected $order;
    protected $orderItems;
    protected $carts;
    protected $product;
    protected $transcation;
    protected $address;
    protected $status;
    protected $supplier;
    protected $barcode;
    protected $user;
    protected $notification;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItems = new OrderItem();
        $this->carts = new Cart();
        $this->product = new Product();
        $this->transcation = new Transcation();
        $this->address = new Address();
        $this->status = new Status();
        $this->supplier = new Supplier();
        $this->barcode = new OrderBarcode();
        $this->user = new User();
        $this->notification = new Notification();
    }

    public function index()
    {
        $status = $this->status->all();
        $supplier = $this->supplier->where('type', 2)->get();
        $orders = $this->order
            ->join('users', 'orders.user_id', 'users.id')
            ->join('transactions', 'orders.id', 'transactions.order_id', 'left')
            ->orderBy('id', 'desc')
            ->select('orders.*', 'users.name as user_name', 'transactions.payment_id')
            ->paginate(config('constants.pagination_limit'));
            // dd($orders);
        return view('orders.index', compact('orders', 'status', 'supplier'));
    }

    public function export()
    {
        $orders = $this->order
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.order_no',
                'users.name as user_name',
                'orders.total_amount',
                'orders.total_discount',
                'orders.final_amount',
                'orders.status',
                'orders.payment_status',
                'orders.payment_method',
                'orders.created_at'
            )
            ->orderBy('orders.id', 'desc')
            ->get();

        $fileName = 'orders_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Order Number', 'User', 'Amount', 'Discount', 'Total Amount', 'Status', 'Payment Status', 'Payment Method', 'Date']);

            foreach ($orders as $index => $order) {
                fputcsv($file, [
                    $index + 1,
                    $order->order_no,
                    $order->user_name,
                    $order->total_amount,
                    $order->total_discount,
                    $order->final_amount,
                    $order->status,
                    $order->payment_status,
                    $order->payment_method,
                    $order->created_at,
                ]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $order = Order::find($request->id);
            $user = User::find($order->user_id);
            $order->update([
                'status' => $request->status
            ]);

            if ($request->status == 'completed') {

                $alreadyGiven = DB::table('points_transactions')
                    ->where('order_id', $order->id)
                    ->where('type', 'credit')
                    ->exists();

                if (!$alreadyGiven) {

                    $user = User::find($order->user_id);

                    $rewardPercent = 5;

                    $points = ($order->final_amount * $rewardPercent) / 100;

                    DB::table('points_transactions')->insert([
                        'user_id'     => $user->id,
                        'order_id'    => $order->id,
                        'type'        => 'credit',
                        'points'      => $points,
                        'description' => 'Points earned from Order #' . $order->order_no,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);

                    $user->wallet_points += $points;
                    $user->save();
                }
            }

            if ($request->status == 'Cancelled') {
                  $points = $order->final_amount;
                  $user->wallet_points += $points;
                  $user->save();
                  $this->notification->create([
                    'user_id' => $user->id,
                    'title' => 'Order Cancelled',
                    'description' => 'Your order #' . $order->order_no . ' has been cancelled. Amount has been refunded to your wallet.'
                ]);
                $order->update([
                    'payment_status' => 'Refunded'
                ]);
            }

            $lastStatus = TruckOrder::where('order_id', $request->id)
                ->latest()
                ->first();

            if (!$lastStatus || $lastStatus->truck_status != $request->status) {

                TruckOrder::create([
                    'order_id' => $request->id,
                    'truck_status' => $request->status
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deliveryBoy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|integer'
        ]);

        try {

            $order = Order::find($request->id);

            $order->delhivery_boy_id = $request->status;
            $order->save();

            $this->notification->create([
                'order_id' => $order->id,
                'title' => 'New Delivery Assigned',
                'description' => 'You have been assigned to deliver order #' . $order->order_no
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Delivery Boy assigned successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function barcode($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Order id not found!');
        }

        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        $awbNumber = $order->order_no;
        $barcodeBase64 = $this->generateBarcode($awbNumber);

        $order->update([
            'barcode' => $barcodeBase64
        ]);

        $this->barcode->create([
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'barcode' => $barcodeBase64
        ]);

        return redirect()->back()->with('success', 'Barcode generated successfully!');
    }

    private function generateBarcode($awb)
    {
        $dns1d = new DNS1D();

        $barcodeBase64 = $dns1d->getBarcodePNG($awb, 'C128', 2, 60);
        $barcodeImage = imagecreatefromstring(base64_decode($barcodeBase64));

        $barcodeWidth  = imagesx($barcodeImage);
        $barcodeHeight = imagesy($barcodeImage);

        $finalHeight = $barcodeHeight + 30;
        $finalImage  = imagecreatetruecolor($barcodeWidth, $finalHeight);

        $white = imagecolorallocate($finalImage, 255, 255, 255);
        $black = imagecolorallocate($finalImage, 0, 0, 0);

        imagefill($finalImage, 0, 0, $white);

        imagecopy($finalImage, $barcodeImage, 0, 0, 0, 0, $barcodeWidth, $barcodeHeight);

        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($awb);
        $textX = ($barcodeWidth - $textWidth) / 2;
        $textY = $barcodeHeight + 5;

        imagestring($finalImage, $fontSize, $textX, $textY, $awb, $black);

        ob_start();
        imagepng($finalImage);
        $imageData = ob_get_clean();

        imagedestroy($barcodeImage);
        imagedestroy($finalImage);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    public function barcodes()
    {
        $barcodes = $this->barcode->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('orders.barcodes', compact('barcodes'));
    }

    public function barcode_print(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()->back()->with('error', 'Please select orders');
        }

        $orders = $this->barcode->whereIn('id', $ids)->get();

        return view('orders.print_barcode', compact('orders'));
    }

    public function invoice($id)
    {

        if (!$id) {
            abort(404, 'Id is required');
        }

        $order = $this->order
            ->with([
                'items' => function ($query) {
                    $query->select('id', 'order_id', 'product_id', 'qty', 'price', 'discount', 'final_price');
                },
                'items.product' => function ($query) {
                    $query->select('id', 'name', 'image', 'sku_code', 'hsn_code', 'barcode_base');
                }
            ])
            ->where('id', $id)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $user = $this->user->where('id', $order->user_id)->first();

        $transaction = $this->transcation
            ->where('order_id', $order->id)
            ->latest('id')
            ->first();

        $defaultAddress = $this->address
            ->where('id', $user->address_id)
            ->where('is_default', 1)
            ->first();

        if (!$defaultAddress) {
            $defaultAddress = $this->address
                ->where('user_id', $user->id)
                ->where('is_default', 1)
                ->first();
        }

        $data = [
            'order' => $order,
            'items' => $order->items,
            'transaction' => $transaction,
            'address' => $defaultAddress,
            'user' => $user
        ];

        // dd($data);

        $pdf = Pdf::loadView('orders.invoice', $data);

        return $pdf->download('invoice-' . $order->order_no . '.pdf');
    }
}
