<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    use HasFactory;
    protected $table = 'card_types';
    protected $fillable = [
        'name',
        'discount_percent',
        'status',
        'image',
        'description',
    ];
}
