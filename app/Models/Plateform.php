<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plateform extends Model
{
    use HasFactory;
    protected $table = 'selling_platforms';
    protected $fillable = [
        'name',
        'slug',
        'website_url',
        'status',
        'logo',
        'created_at',
        'updated_at',
    ];

     public function productPartners()
    {
        return $this->hasMany(ProductPartner::class, 'platform_id', 'id');
    }
    
}
