<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarientValue extends Model
{
    use HasFactory;
    protected $table = 'product_variant_values';
    protected $fillable = ['id', 'variant_id', 'attribute_id', 'attribute_value_id', 'created_at', 'updated_at'];
}
