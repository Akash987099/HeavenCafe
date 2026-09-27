<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    protected $table = 'pos_order';

    protected $fillable = [
        'pos_user_id',
        'store_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'discount',
        'grand_total',
        'fulfillment_type',
        'status',
        'payment_method',
        'payment_status',
        'payment_gateway',
        'payu_txnid',
        'payu_payment_id',
        'payu_response',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
    ];

    protected $casts = ['payu_response' => 'array'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Customer self-order views use `items`; POS screens use `details`.
    public function items()
    {
        return $this->details();
    }

    public function getCustomerMobileAttribute(): ?string
    {
        return $this->customer_phone;
    }


    public function details()
    {
        return $this->hasMany(
            PosOrderDetail::class,
            'pos_order_id'
        );
    }
}
