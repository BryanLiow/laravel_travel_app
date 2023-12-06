<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import the User model

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment'];

    // Define a relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
