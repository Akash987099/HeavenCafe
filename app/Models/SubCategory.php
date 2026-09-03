<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_category';
    protected $fillable = ['id', 'category_id', 'name', 'image', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subcategory', 'sub_category_id', 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category');
    }
}
