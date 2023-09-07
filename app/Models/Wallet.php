<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory; use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $casts = [
        'created_at' =>  'datetime:dS F, Y, H:i a',
         
     ];
     public function user(){
         return $this->belongsTo(User::class,'created_user_id');
     }
     public function admin(){
         return $this->belongsTo(User::class,'receiver_id');
     }
     public function payment(){
         return $this->belongsTo(Payment::class,'payment_method');
     }
}
