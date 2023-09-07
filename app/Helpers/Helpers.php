<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Vat;
use App\Models\Shop;
use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Slider;
use App\Models\Wallet;
use App\Models\Country;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\District;
use App\Models\Division;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Models\ShopCurrentStock;
use App\Models\Thana;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Request;


class Helper
{



    public static function setting()
    {
        return Cache::rememberForever('setting', function () {
            return Setting::firstOrFail();
        });
    }

    public static function adminSetting()
    {

        return Profile::where('user_id', Auth::id())->first();
    }
    public static function getProfile($id)
    {

        return Profile::whereuser_id($id)->first();
    }

    public static function getSlider()
    {

        return Slider::wherestatus(1)->inRandomOrder()->first();;
    }
    public static function getDivisiontPluck()
    {
        return   Division::select(
            DB::raw("CONCAT(division,' - ',bn_division) AS name"),
            'division'
        )->pluck('name','division');
    }
    public static function employeeCount($adminId)
    {

        return User::wherestaff_id($adminId)->count('id');
    }

    
    public static function getPaymentMethodName($id)
    {
        return Payment::find($id);
    }

    public static function paymentMethodPluckValue()
    {
        return Payment::wherestatus(1)->pluck('payment_name', 'id');
    }
    public static function paymentPluckValue()
    {
        return Payment::wherestatus(1)->pluck('payment_name', 'payment_name');
    }

    public static function adminPluckValue()
    {
        return User::whereuser_type('Admin')->pluck('name', 'id');
    }

    public static function discoutPluckValue()
    {
        return Cache::rememberForever('discountpluck', function () {
            return Discount::pluck('discount', 'discount');
        });
    }
    public static function vatPluckValue()
    {
        return Cache::rememberForever('vatpluck', function () {
            return Vat::pluck('vat', 'vat');
        });
    }
    public static function categoryPluckValue()
    {
        return Cache::rememberForever('categorypluck', function () {
            return Category::pluck('category_name', 'id');
        });
    }
    public static function countryPluckValue()
    {
        return Cache::rememberForever('countrypluck', function () {
            return Country::pluck('country_name', 'country_name');
        });
    }
    public static function Permissions()
    {
        return  Permission::get();
    }
    public static function packageEndDate($package, $date)
    {
        $dayes = Package::find($package)->duration;
        return Carbon::create($date)->addDays($dayes)->format("Y-m-d");
    }
    public static function getBlance($id)
    {

        return Wallet::whereuser_id($id)->get();
    }
    public static function getadminPaymentReceiver()
    {

        return User::whereuser_type('Admin')->pluck('name', 'id');
    }
   
    public static function shopCurrentStocks()
    {
        if (Auth::user()->user_type == 'SuperAdmin') {
            return ShopCurrentStock::sum('stock_qty');
        } elseif (Auth::user()->user_type == 'Admin') {
            return ShopCurrentStock::whereadmin_id(Auth::id())->sum('stock_qty');
        } else {
            return ShopCurrentStock::whereadmin_id(Auth::user()->admin_id)->sum('stock_qty');
        }
    }
    public static function supplierPluckValue()
    {
        if (Auth::user()->user_type == 'SuperAdmin') {
            return Supplier::wherestatus(1)->pluck('supplier_name', 'id');
        } elseif (Auth::user()->user_type == 'Admin') {
            return Supplier::wherestatus(1)->whereadmin_id(Auth::id())->pluck('supplier_name', 'id');
        } else {
            return Supplier::wherestatus(1)->whereadmin_id(Auth::user()->admin_id)->pluck('supplier_name', 'id');
        }
    }
    public static function customerPluckValue()
    {
        if (Auth::user()->user_type == 'SuperAdmin') {
            return Customer::wherestatus(1)->pluck('customer_name', 'id');
        } elseif (Auth::user()->user_type == 'Admin') {
            return Customer::wherestatus(1)->whereadmin_id(Auth::id())->pluck('customer_name', 'id');
        } else {
            return Customer::wherestatus(1)->whereadmin_id(Auth::user()->admin_id)->pluck('customer_name', 'id');
        }
    }
    

    public static function getCountryName()
    {
        return Country::pluck('country_name', 'country_name');
    }

    //for location
    public static function divisionMigrate()
    {
        $headers = [
            'accept' => 'application/json',
            'X-RapidAPI-Key' => '316b564a4cmshfe4c46e5ca27667p1d623djsn64745f806c6b',
            'X-RapidAPI-Host' => 'bdapi.p.rapidapi.com',
            'Content-Type' => 'application/json'
        ];
        $info = Http::withHeaders($headers)->get('https://bdapi.p.rapidapi.com/v1.1/divisions');
        $apiData = json_decode($info->body());

        foreach ($apiData->data as $location) {
            $division = new Division();
            $division->country_id = 1;
            $division->division = $location->division;
            $division->bn_division = $location->divisionbn;
            $division->save();
        }
    }

    public static function districtMigrate()
    {
        $headers = [
            'accept' => 'application/json',
            'X-RapidAPI-Key' => '316b564a4cmshfe4c46e5ca27667p1d623djsn64745f806c6b',
            'X-RapidAPI-Host' => 'bdapi.p.rapidapi.com',
            'Content-Type' => 'application/json'
        ];

        $Division = Division::get();
        for ($i = 0; $i < count($Division); $i++) {
            $info = Http::withHeaders($headers)->get('https://bdapi.p.rapidapi.com/v1.0/division/' . $Division[$i]->division);
            $apiData = json_decode($info->body());

            foreach ($apiData->data as $location) {
                $district = new District();
                $district->division_id = $Division[$i]->id;
                $district->district = $location->district;
                $district->bn_district = $location->district;
                $district->save();
                foreach ($location->upazilla as $upazila) {
                    $thana = new Thana();
                    $thana->district_id = $district->id;
                    $thana->thana = $upazila;
                    $thana->bn_thana = $upazila;
                    $thana->save();
                }
            }
        }
    }
}
