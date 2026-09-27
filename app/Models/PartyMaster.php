<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyMaster extends Model
{
    use HasFactory;

    protected $table = 'party_masters';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'status', 'created_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
