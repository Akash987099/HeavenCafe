<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrderItem extends Model
{
    protected $table = 'store_order_items';

    protected $fillable = [
        'store_order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
