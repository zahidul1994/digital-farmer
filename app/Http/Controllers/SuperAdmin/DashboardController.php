<?php

namespace App\Http\Controllers\SuperAdmin;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Dashboard"],
            ['name' => 'Dashboard'],
        ];   
        return view('backend.superadmin.dashboard', compact('breadcrumbs'));
    }
}
