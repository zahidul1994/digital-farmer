<?php
namespace App\Http\Controllers\Superadmin;
use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    public function index(Request $request)
    {
        try {
           
            $settingInfo = Setting::first();
            $profileInfo= User::with('profile')->find(Auth::id());
            return view('backend.superadmin.setting.index',compact('settingInfo','profileInfo'));
           
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }
   

    public function profilesUpdate(Request $request)
    {
       
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'description' => 'required|min:1|max:5000',
            'phone' => 'required|min:9|max:30|unique:users,email,'.Auth::id(),
            'gender' => 'required',
            'country' => 'required',
            'company_name' => 'required',
            'company_address' => 'required',
            
         
        ]);
                
                $user = User::find(Auth::id());
                $name = $request->name;
                $user->name=$name;
                $user->phone=$request->phone;
               $user->email = $request->email;
                $user->ip_address = $request->ip();
                
                if ($request->hasFile('image')) {
                    $this->validate($request, [
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
    
                    ]);
                   
                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/", 0777, true);
                    }
                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/", 0777, true);
                    }

                    $ex = $request->image->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->name, 40)));
                    $name = $rand . "." . $ex;
                    $request->image->move(storage_path('/app/public/files/shares/uploads/' . Auth::id() . '/'), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);
                    
                    $user->image =url('storage/files/shares/uploads/'.Auth::id()).'/'. $name;
                }
               
                $user->updated_user_id =  Auth::id();
                if ($user->save()) {
                    $profile = Profile::whereuser_id(Auth::id())->first();
                    $profile->position = $request->position;
                    $profile->gender = $request->gender;
                   $profile->country = $request->country;
                   $profile->gender = $request->gender;
                   $profile->company_name = $request->company_name;
                   $profile->company_address = $request->company_address;
                   $profile->web_address = $request->web_address;
                  if ($request->hasFile('company_logo')) {
                    $this->validate($request, [
                        'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
    
                    ]);
                   
                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/", 0777, true);
                    }
                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/", 0777, true);
                    }

                    $ex = $request->company_logo->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->company_name, 40)));
                    $name = $rand . "." . $ex;
                    $request->company_logo->move(storage_path('/app/public/files/shares/uploads/' . Auth::id() . '/'), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);
                    
                    $profile->company_logo =url('storage/files/shares/uploads/'.Auth::id()).'/'. $name;
                }


                    $profile->save();
                    Toastr::success("Profile Update Successfully", "Success");
                    return redirect()->route(request()->segment(1) . '.profiles');
        
                }
                else{
                    dd(1);
                }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function passwordUpdate(Request $request)
    {
       
        $this->validate($request, [
            'password' => 'required|min:6|max:30',
            'confirm' => 'required|same:password',
      
          ]);
          if (!Hash::check($request->currentpassword, Auth::user()->password)) {
            Toastr::success("Current password wrong", "Warning");
            return redirect()->route(request()->segment(1) . '.profiles');
          } else {
      
            User::find(Auth::user()->id)->update(array('remember_token' => null));
            $userinfo = User::find(Auth::user()->id)->update(array(
              'password' => Hash::make($request->confirm),
            ));
          }
          if ($userinfo) {
            Toastr::success("Password change successfully done", "Success");
            Auth::logout();
            return back();
           
          }
    }


}
