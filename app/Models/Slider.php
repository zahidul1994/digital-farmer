<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:d M Y H : m',
        
    ];
    
       public function user(){
            return $this->belongsTo('App\Models\User','created_user_id','id')->select('id','name');
        }
}
