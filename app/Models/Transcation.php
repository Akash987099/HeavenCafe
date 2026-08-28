<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcation extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'order_id',
        'payment_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_type',
        'gateway',
        'status',
        'payment_status',
        'gateway_response',
        'failure_reason',
        'paid_at'
    ];
    const UPDATED_AT = null;
}
