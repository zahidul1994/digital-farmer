<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use App\Models\Passport;
use App\Models\Supplier;
use App\Models\Subcategory;
use App\Models\SupplierDue;
use App\Models\Thana;
use Illuminate\Http\Request;

class OnChangeController extends Controller
{
    public function getDistrict($id){
        return Division::with('district')->wheredivision($id)->first();
    } 
    
    public function getThana($id){
     return District::with('thana')->wheredistrict($id)->first();
    }
    public function getArea(Request $request){
     // return ($request->all());
       $district= District::wheredistrict($request->district)->first();
     $thana=Thana::wheredistrict_id($district->id)->wherethana($request->thana)->first();
     return Area::wherethana_id($thana->id)->get();
     
    }

    
}
