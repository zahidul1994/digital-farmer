<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCurrentStock extends Model
{
    use HasFactory; 
    protected $casts = [
        'created_at' =>  'datetime:dS F, Y',
        ];
        public function admin(){
            return $this->belongsTo(User::class,'admin_id');
        }
        public function shop(){
            return $this->belongsTo(Shop::class,'shop_id');
        }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
