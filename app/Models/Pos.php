<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pos extends Authenticatable
{
    use HasFactory;
    protected $table = 'pos';
    protected $fillable = ['id', 'name', 'email', 'mobile', 'role', 'user_id', 'password', 'store_id', 'created_at', 'updated_at'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
