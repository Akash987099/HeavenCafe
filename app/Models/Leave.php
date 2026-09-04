<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'pos_leaves';

    protected $fillable = [
        'pos_user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'manager_remark',
    ];

    public function pos()
    {
        return $this->belongsTo(Pos::class, 'pos_user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(Pos::class, 'approved_by', 'id');
    }
}