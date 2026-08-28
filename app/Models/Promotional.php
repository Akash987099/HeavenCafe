<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotional extends Model
{
    use HasFactory;
    protected $table = "promotionals";
    protected $fillable = ['id', 'name', 'url_link', 'image', 'status', 'position', 'created_at', 'updated_at'];
}
