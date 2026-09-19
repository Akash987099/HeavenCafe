<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $fillable = [
        'store_id',
        'order_number',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'payment_status',
        'fulfillment_type',
        'status',
        'subtotal',
        'grand_total',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function items()
    {
        return $this->hasMany(CustomerOrderItem::class);
    }
}
