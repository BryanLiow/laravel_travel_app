<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    protected $fillable = ['email', 'token'];
    // If you wish to use timestamps (created_at and updated_at), set $timestamps to true
    public $timestamps = false;
}
