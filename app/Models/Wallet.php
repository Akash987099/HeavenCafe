<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'points_transactions';
    protected $fillable = ['id', 'user_id', 'order_id', 'is_processed ', 'type', 'points', 'description', 'expiry_date', 'created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id')
            ->select('id', 'order_no');
    }
}
