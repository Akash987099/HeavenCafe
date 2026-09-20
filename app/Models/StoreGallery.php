<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreGallery extends Model
{
    protected $table = 'store_galleries';

    protected $fillable = [
        'store_id',
        'title',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
