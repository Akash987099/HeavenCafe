<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAplus extends Model
{
    protected $table = 'product_aplus';

    protected $fillable = [
        'product_id',
        'section_type'
    ];

    // RELATION → Images
    public function images()
    {
        return $this->hasMany(ProductAplusImage::class, 'aplus_id');
    }

    // RELATION → Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}