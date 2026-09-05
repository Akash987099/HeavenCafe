<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPartner extends Model
{
    use HasFactory;
    protected $table = 'product_platform_links';
    protected $fillable = [
        'product_id',
        'platform_id',
        'product_url',
        'created_at',
        'updated_at',
    ];

     public function partner()
    {
        return $this->belongsTo(Plateform::class, 'platform_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
