<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable=[
    'shortname' ,'language'];
  

    public function admin()

    {
        return $this->hasMany('App\Models\Admin');

    }
}
