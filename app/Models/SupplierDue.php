<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierDue extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' =>  'datetime:dS F, Y, H:i a',
        ];
    public function user(){
        return $this->belongsTo(User::class,'created_user_id','id')->select('id','name');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }
   
}
