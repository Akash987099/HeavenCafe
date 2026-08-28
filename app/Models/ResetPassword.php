<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasFactory;
    protected $table = 'password_resets_temp';
    protected $fillable = ['id', 'user_id', 'password', 'otp', 'otp_expires_at', 'created_at', 'updated_at'];
}
