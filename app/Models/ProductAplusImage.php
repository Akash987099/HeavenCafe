<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAplusImage extends Model
{
    protected $table = 'product_aplus_images';

    protected $fillable = [
        'aplus_id',
        'image'
    ];

    // RELATION → Aplus
    public function aplus()
    {
        return $this->belongsTo(ProductAplus::class, 'aplus_id');
    }
}