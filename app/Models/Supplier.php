<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = ['id', 'name', 'email', 'phone', 'address', 'gst_no', 'type', 'created_at', 'updated_at'];
}