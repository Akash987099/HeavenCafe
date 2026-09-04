<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrderDetail extends Model
{
    protected $table = 'pos_order_details';

    protected $fillable = [
        'pos_order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total',
    ];


    public function order()
    {
        return $this->belongsTo(
            PosOrder::class,
            'pos_order_id'
        );
    }
}