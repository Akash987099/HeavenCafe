<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    protected $table = 'recommended_products';
    protected $fillable = ['product_id', 'recommended_product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}