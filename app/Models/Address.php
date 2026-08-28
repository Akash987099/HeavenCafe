<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = "user_address";
    protected $fillable = ['id', 'user_id', 'country', 'state', 'district', 'tehsil', 'block', 'village', 'address', 'pincode', 'landmark', 'is_default', 'person', 'contact', 'distance', 'time', 'lat', 'lng', 'address_type', 'street', 'created_at', 'updated_at'];
}
