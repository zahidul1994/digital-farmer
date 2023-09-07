<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Usernotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    use AuthenticatesUsers;

   
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        Toastr::success("Successfully Login", "Success");
        if (Auth::check() && (Auth::user()->user_type === 'Superadmin')) {
            $data = [
                'message' => 'Hi ' . Auth::user()->name . ' . Your Last Login  At ' . Auth::user()->last_login,

            ];
            User::first()->notify(new Usernotification($data));
            User::find(Auth::id())->update(array('last_login' => now()));
            return $this->redirectTo = route('superadmin.dashboard');
        } elseif (Auth::check() && (Auth::user()->user_type === 'Admin')) {           
                $data = [
                    'message' => 'Hi ' . Auth::user()->name . ' . Your Last Login  At ' . Auth::user()->last_login,

                ];
                User::find(Auth::id())->notify(new Usernotification($data));
                User::find(Auth::id())->update(array('last_login' => now()));
                return $this->redirectTo = route('admin.dashboard');
        } elseif (Auth::check() && (Auth::user()->user_type === 'Mso')) {
            $userdata = [
                'message' => 'Hi Admin Your Mso ' . Auth::user()->name . 'Just Login',
            ];
            User::find(Auth::user()->admin_id)->notify(new Usernotification($userdata));
            
            return $this->redirectTo = route('mso.dashboard');
        } else {
            return ('/login');
        }
    }
  
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:30'
        ]);
        $remember = (!empty($request->remember)) ? TRUE : FALSE;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'status' => 1], $remember)) {
          
            if (Auth::check() && (Auth::user()->user_type === 'Superadmin')) {
                $data = [
                    'message' => 'Hi ' . Auth::user()->name . ' . Your Last Login  At ' . Auth::user()->last_login,
    
                ];
                User::first()->notify(new Usernotification($data));
                User::find(Auth::id())->update(array('last_login' => now()));
                return  redirect()->intended('superadmin/dashboard');
            } elseif (Auth::check() && (Auth::user()->user_type === 'Admin')) {           
                    $data = [
                        'message' => 'Hi ' . Auth::user()->name . ' . Your Last Login  At ' . Auth::user()->last_login,
    
                    ];
                    User::find(Auth::id())->notify(new Usernotification($data));
                    User::find(Auth::id())->update(array('last_login' => now()));
                    return  redirect()->intended('admin/dashboard');
            } elseif (Auth::check() && (Auth::user()->user_type === 'Mso')) {
                $userdata = [
                    'message' => 'Hi Admin Your Mso ' . Auth::user()->name . 'Just Login',
                ];
                User::find(Auth::user()->admin_id)->notify(new Usernotification($userdata));
                
                return  redirect()->intended('mso/dashboard');
            } else {
                return ('/login');
            }
         }
        if (!User::where('email', $request->email)->where('status', '<>', 0)->first()) {
            return redirect()->back()
                ->withErrors([
                    'status' => "This Account Inactive. Please Contact Your Administrator",
                ]);
        }
        if (!User::where('email', $request->email)->where('status', '<>', 2)->first()) {
            return redirect()->back()
                ->withErrors([
                    'status' => "This Account Has Been Suspended",
                ]);
        }
		else{
            return redirect()->back()
                ->withErrors([
                    'status' => "Email Or Password Invalid !!",
                ]);
        }
       
       
       
    }
    public function logout(Request $request)
    {
        User::find(Auth::id())->update(array('remember_token' => null));
        Auth::logout();
        $request->session()->invalidate();
        return back();
    }
}
