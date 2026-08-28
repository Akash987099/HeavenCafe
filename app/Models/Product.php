<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku_product_id', 
        'client_id', 
        'name',
        'brand_name',
        'image',
        'status',
        'price',
        'ac_price',
        'sku_code',
        'hsn_code',
        'tags',
        'meta_tag',
        'category',
        'sub_category',
        'discount',
        'brands',
        'barcode_base',
        'stock',
        'in_stock',
        'summer_id',
        'slug',
        'short_description',
        'description',
        'similar',
        'product_type',
        'type',
        'type_value',
    ];

    public function recommendedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'recommended_products',
            'product_id',
            'recommended_product_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function userReview()
    {
        return $this->hasOne(Review::class, 'product_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function comboItems()
    {
        return $this->hasMany(Combo::class, 'combo_product_id');
    }

}
