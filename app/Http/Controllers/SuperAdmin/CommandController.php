<?php

namespace App\Http\Controllers\Superadmin;

use Exception;
use App\Models\RehabCenter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;


class CommandController extends Controller
{


    public function index()
    {
        return view('backend.superadmin.setting.artisan');
    }

    public function artisan($command, $param)
    {
        if ($command == 'migrate') {
            Artisan::call('migrate');
            Toastr::info("Your command $command  successfully done", "Success");
            return back();
        } elseif ($command == 'flush') {
            Cache::flush();
            Toastr::info("Your command $command  successfully done", "Success");
            return back();
        }  elseif ($param == 'up') {
            Artisan::call('up');
            Toastr::info("Your command $command  successfully done", "Success");
            return back();
        } elseif ($param == 'down') {
            Artisan::call('down');
            Toastr::info("Your command $command  successfully done", "Success");
            return back();
        }
         elseif ($command == 'cacheall') {
            Artisan::call('view:cache');
            Artisan::call('config:cache');
            Artisan::call('event:cache');
            Artisan::call('route:cache');
            Toastr::info("Your command Site Optimize  successfully done", "Success");
            return back();
        }
         
        Artisan::call($command . ":" . $param);
        Toastr::info("Your command $command $param successfully done", "Success");
        return back();
    }

public function debugon(){
    
        try {
            $path = app()->environmentFilePath();
            $contents = File::get($path);
            // dd($contents);
            if (env('APP_DEBUG') == 'true') {
                $contents = str_replace('\'APP_DEBUG\' = false', '\'APP_DEBUG\' = false', $contents . "\n");
            } else {
                $contents = str_replace('\'APP_DEBUG\' = false', '\'APP_DEBUG\' = false', $contents . "\n");
            }
            File::put($path, $contents);

            Toastr::info("Your SMTP successfully done", "Success");
            return back();
        } catch (Exception $e) {
            Toastr::info("Your command fail", "Warning");
            return back();
        }
    
}


}
