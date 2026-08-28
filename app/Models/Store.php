<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'store';
    protected $fillable = ['id', 'name', 'address', 'city' , 'zipcode', 'description', 'image', 'latitude', 'longitude', 'created_at', 'updated_at'];
}