<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'show_id',
        'name',
        'genres',
        'image',
        'type',
    ];
}
