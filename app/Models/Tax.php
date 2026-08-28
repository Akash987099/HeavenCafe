<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $table = 'taxes';
    protected $fillable = ['id', 'name', 'tax_value', 'status', 'created_at', 'updated_at']; 
}
