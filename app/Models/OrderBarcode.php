<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBarcode extends Model
{
    use HasFactory;
    protected $table = 'order_barcodes';
    protected $fillable = ['id', 'order_id', 'order_no', 'barcode', 'created_at', 'updated_at'];
}
