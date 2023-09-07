<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Mso
{
    
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->user_type == 'Mso')) {
            return $next($request);
        }
        else{
          return redirect()->route('login');
        }
    }
}
