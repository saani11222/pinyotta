<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendInvite extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'random_token',
    ];
}
