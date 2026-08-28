<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transcation;

class ReportController extends Controller
{
    protected $transaction;

    public function __construct()
    {
        $this->transaction = new Transcation();
    }

    public function transaction()
    {
        $transcation = $this->transaction
            ->join('orders', 'transactions.order_id', 'orders.id')
            ->select('transactions.*', 'orders.order_no')
            ->orderBy('transactions.id', 'desc')
            ->paginate(config('constants.pagination_limit'));

        return view('transaction', compact('transcation'));
    }
}
