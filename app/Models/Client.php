<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientAddress;
use App\Models\Category;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'company_name',
        'category_id',
        'gst_number',
        'password',
        'api_key',
        'api_secret',
        'pickup_address',
        'pickup_city',
        'pickup_state',
        'pickup_pincode',
        'return_address',
        'return_city',
        'return_state',
        'return_pincode',
        'status',
    ];

    // Relationship
    public function addresses()
    {
        return $this->hasMany(ClientAddress::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
