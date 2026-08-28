<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = "settings";
    protected $fillable = ['id', 'name', 'slug', 'image', 'description', 'media_link', 'created_at', 'updated_at'];
}
