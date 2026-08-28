<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['user_id', 'address_id', 'order_type', 'table_no', 'reward_points', 'delhivery_boy_id', 'barcode', 'order_no', 'total_amount', 'total_discount', 'final_amount', 'status', 'delhivery_charge', 'payment_status', 'payment_method', 'description', 'created_at', 'updated_at'];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_items',
            'order_id',
            'product_id'
        )->select('products.id', 'products.name', 'products.image');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id', 'id');
    }

    public function orderRating()
    {
        return $this->hasOne(OrderRating::class, 'order_id', 'id')
            ->select('id', 'order_id', 'rating', 'review');
    }
}
