<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalaryAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'user_id',
        'amount',
        'advance_date',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'advance_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Pos::class, 'staff_id');
    }
}
