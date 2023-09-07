<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    public function admin(){
        return $this->belongsTo(User::class,'admin_id','id');
    }
    public function employee(){
        return $this->belongsTo(User::class,'employee_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_user_id','id')->select('id','name');
    }
}
