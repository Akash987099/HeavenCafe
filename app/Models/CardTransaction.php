<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory;
    protected $table = 'card_transactions';
    protected $fillable = [
        'card_id',
        'user_id',
        'order_id',
        'amount',
        'type',
        'description',
    ];
}
