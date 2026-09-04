<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $table = 'pos_policies';
    protected $fillable = ['id', 'name', 'pdf', 'created_at', 'updated_at'];
}
