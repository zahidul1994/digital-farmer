<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    
    public function user(){
        return $this->belongsTo(User::class,'created_user_id','id')->select('id','name');
    }
    public function supplierdue(){
        return $this->hasMany(SupplierDue::class,'supplier_id','id');
    }
    public function purchase(){
        return $this->hasMany(Purchase::class,'supplier_id','id')->select('id','grand_total','paid','due');
    }
}
