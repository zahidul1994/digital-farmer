<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoUpload extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:d M Y',
        
    ];
    
       public function user(){
            return $this->belongsTo('App\Models\User')->select('id','name');
        }
}
