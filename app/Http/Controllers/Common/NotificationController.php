<?php

namespace App\Http\Controllers\Common;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    
    public function index()
    {
       try{
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Dashboard"],
            ['name' => 'Notification'],
        ];   
            return view('backend.common.notifications.index', compact('breadcrumbs'));
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

  
}
