<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varient extends Model
{
    use HasFactory;
    protected $table = 'product_variants';
    protected $fillable = ['id', 'product_id', 'sku', 'price', 'stock', 'image', 'created_at', 'updated_at'];
}
