<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory; use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $casts = [
        'created_at' =>  'datetime:dS F, Y, H:i a',
        ];
    public function user(){
        return $this->belongsTo(User::class,'created_user_id','id')->select('id','name');
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function saledetails(){
        return $this->hasMany(SaleDetails::class);
    }
}
