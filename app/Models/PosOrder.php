<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    protected $table = 'pos_order';

    protected $fillable = [
        'pos_user_id',
        'order_number',
        'subtotal',
        'discount',
        'grand_total',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
    ];


    public function details()
    {
        return $this->hasMany(
            PosOrderDetail::class,
            'pos_order_id'
        );
    }
}