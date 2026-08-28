<?php

namespace App\Models\cafe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'cafe_category';
    protected $fillable = ['id', 'name', 'image', 'slug', 'position', 'created_at', 'updated_at'];

}
