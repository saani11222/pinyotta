<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminModulePage extends Model
{
    use HasFactory;
    protected $table = 'admin_module_pages';
    public function moduless()
	{
	    return $this->belongsTo(AdminModule::class);
	}  
    
}
