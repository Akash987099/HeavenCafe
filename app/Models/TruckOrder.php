<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckOrder extends Model
{
    use HasFactory;
    protected $table = "truck_orders";
    protected $fillable = ['id', 'order_id', 'truck_status', 'created_at', 'updated_at'];
}
