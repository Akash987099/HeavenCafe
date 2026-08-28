<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    protected $table = 'cards';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'mobile',
        'email',
        'card_type_id',
        'card_number',
        'card_name',
        'balance',
        'status',
        'is_primary',
        'expiry_date',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function cardType()
    {
        return $this->belongsTo(\App\Models\CardType::class, 'card_type_id');
    }
}
