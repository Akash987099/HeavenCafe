<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class ClientAddress extends Model
{
    use HasFactory;

    protected $table = 'client_addresses';

    protected $fillable = [
        'client_id',
        'type',
        'address',
        'city',
        'state',
        'pincode',
    ];

    // Relationship
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}