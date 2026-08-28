<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    use HasFactory;
    protected $table = 'points_settings';
    protected $fillable = [
        'id',
        'reward_percent',
        'point_value',
        'max_redeem_percent',
        'min_order_amount',
        'expiry_days',
        'created_at',
        'updated_at',
    ];
}
