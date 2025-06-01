<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchList extends Model
{
    public $timestamps = true;
    protected $table = 'watch_list';
    protected $fillable = [
        // 'user_id',
        // 'show_id',
        // 'name',
        // 'genres',
        // 'image',
        // 'type',
    ];
}
