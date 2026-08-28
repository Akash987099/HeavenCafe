<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    use HasFactory;
    protected $table = 'cms';
    protected $fillable = ['id', 'name', 'url', 'category', 'status', 'description', 'created_at', 'updated_at'];
}
