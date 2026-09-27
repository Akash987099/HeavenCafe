<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_master_id', 'store_id', 'customer_name', 'mobile', 'email', 'event_date',
        'event_time', 'guest_count', 'venue', 'requirements', 'status',
    ];

    protected $casts = ['event_date' => 'date'];

    public function partyMaster()
    {
        return $this->belongsTo(PartyMaster::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function posOrders()
    {
        return $this->hasMany(PosOrder::class);
    }
}
