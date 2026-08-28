<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class UserController extends Controller
{
    protected $user;
    protected $cart;
    protected $order;
    protected $orderitem;

    public function __construct()
    {
        $this->user = new User();
        $this->cart = new Cart();
        $this->order = new Order();
        $this->orderitem = new OrderItem();
    }

    public function index()
    {
        $users = $this->user->paginate(config('constants.pagination_limit'));
        return view('wholesale.index', compact('users'));
    }

    public function export()
    {
        $users = $this->user->orderBy('id', 'desc')->get(['name', 'email', 'phone', 'status']);
        $fileName = 'users_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($users) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Email', 'Phone', 'Status']);

            foreach ($users as $index => $user) {
                fputcsv($file, [$index + 1, $user->name, $user->email, $user->phone, $user->status]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function status(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $user = $this->user->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $user->status = $status;
        $user->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function cart($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $carts = $this->cart->with('product')->where('user_id', $id)->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));

        return view('wholesale.cart', compact('carts'));
    }

    public function order($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $orders = $this->order->where('user_id', $id)->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));

        return view('wholesale.order', compact('orders'));
    }

    public function orderDetails($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $orders = $this->orderitem
            ->with(['product', 'order'])
            ->where('order_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));

        return view('wholesale.order_details', compact('orders'));
    }
}
