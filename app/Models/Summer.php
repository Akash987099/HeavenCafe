<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summer extends Model
{
    use HasFactory;
    protected $table = 'summer';
    protected $fillable = ['id', 'name' , 'sub_name', 'position', 'status', 'time', 'image', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Product::class, 'summer_id', 'id')
                    ->where('status', 'active');
    }
    
}