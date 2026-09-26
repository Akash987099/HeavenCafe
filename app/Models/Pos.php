<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pos extends Authenticatable
{
    use HasFactory;
    protected $table = 'pos';
    protected $fillable = [
        'id', 'name', 'email', 'mobile', 'role', 'user_id', 'password', 'store_id',
        'date_of_joining', 'date_of_birth', 'gender', 'designation', 'salary', 'address',
        'emergency_contact_name', 'emergency_contact_mobile', 'bank_name',
        'bank_account_number', 'bank_ifsc_code', 'staff_image', 'documents', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
        'documents' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function roleMaster()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }
}
