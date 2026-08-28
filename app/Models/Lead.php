<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $fillable = [
        'id',
        'user_id',
        'card_type_id',
        'crn',
        'status',
        'name',
        'phone',
        'email',
        'created_at',
        'updated_at',
    ];

    public function cardType()
    {
        return $this->belongsTo(\App\Models\CardType::class, 'card_type_id');
    }
}
