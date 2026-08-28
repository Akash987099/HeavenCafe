<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class village extends Model
{
    use HasFactory;
    protected $table = 'villages';
    protected $fillable = ['id', 'block_id', 'name', 'created_at', 'updated_at'];
}
