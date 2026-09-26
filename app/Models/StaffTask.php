<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'user_id',
        'title',
        'description',
        'task_image',
    ];

    public function staff()
    {
        return $this->belongsTo(Pos::class, 'staff_id');
    }
}
