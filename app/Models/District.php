<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    
    
    public function division()

    {
        return $this->belongsTo('App\Models\Division');

    }
    public function thana()

    {
        return $this->hasMany('App\Models\Thana');

    }
}
