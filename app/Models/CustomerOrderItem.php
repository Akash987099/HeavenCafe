<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderItem extends Model
{
    protected $fillable = [
        'customer_order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }
}
