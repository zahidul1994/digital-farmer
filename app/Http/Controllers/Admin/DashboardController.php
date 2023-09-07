<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mofa;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\User;
use App\Models\Agent;
use App\Models\Finger;
use App\Models\Flight;
use App\Models\Income;
use App\Models\Slider;
use App\Models\BioData;
use App\Models\Medical;
use App\Models\Product;
use App\Models\Manpower;
use App\Models\Passport;
use App\Models\Purchase;
use App\Models\Training;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\DailyReceive;
use Illuminate\Http\Request;
use App\Models\DepartureCard;
use App\Models\OfficeExpense;
use App\Models\CandidateFlight;
use App\Models\ParentalObjection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Helper\Sample;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['name' => 'Dashboard'],
        ];
        $users=User::whereadmin_id(Auth::id())->get(['id']);
       
     
      
      // $candidates=Candidate::whereadmin_id(Auth::id())->get(['id']);
        // $candidateflights=CandidateFlight::whereadmin_id(Auth::id())->get(['id']);
        // $dailyreceives=DailyReceive::whereadmin_id(Auth::id())->get(['id']);
        // $departurecards=DepartureCard::whereadmin_id(Auth::id())->get(['id']);
        // $fingers=Finger::whereadmin_id(Auth::id())->get(['id']);
        // $flights=Flight::whereadmin_id(Auth::id())->get(['id']);
        // $incomes=Income::whereadmin_id(Auth::id())->get(['id']);
        // $manpowers=Manpower::whereadmin_id(Auth::id())->get(['id']);
        // $medicals=Medical::whereadmin_id(Auth::id())->get(['id']);
        // $mofas=Mofa::whereadmin_id(Auth::id())->get(['id']);
        // $trainings=Training::whereadmin_id(Auth::id())->get(['id']);
        // $officeexpenses=OfficeExpense::whereadmin_id(Auth::id())->get(['id']);
        // $parentalObjections=ParentalObjection::whereadmin_id(Auth::id())->get(['id']);
        $sliders=Slider::wherestatus(1)->get();
        $previewyear=User::whereadmin_id(Auth::id())->orderBy('created_at')->whereYear('created_at', date("Y",strtotime("-1 year")))->get(['id','created_at'])
        ->groupBy(function ($date)
         {return $date->created_at;
        })
        ->map(function ($group) {
            return 110;
        })->union(array_fill(1, 12, 0))
        ->sortKeys()
        ->toArray();
        $currentyear=User::whereadmin_id(Auth::id())->orderBy('created_at')->whereYear('created_at', date('Y'))->get(['id','created_at'])
        ->groupBy(function ($date)
         {return $date->created_at;
        })
        ->map(function ($group) {
            return 100;
        })->union(array_fill(1, 12, 0))
        ->sortKeys()
        ->toArray();

     return view('backend.admin.dashboard', compact('breadcrumbs','users','sliders','previewyear','currentyear'));
    }
}


