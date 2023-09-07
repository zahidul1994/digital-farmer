<?php

namespace App\Http\Controllers\Common;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
            if ($this->User->status == 0) {
                $request->session()->flush();
                return redirect('login');
            }
            return $next($request);
        });

        $this->middleware('permission:user-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = User::with('shop')->whereuser_type('Employee')->latest();
            } elseif ($User->user_type == 'Admin') {
                $data = User::whereuser_type('Mso')->latest();
            } else {
                $data = User::wheremso_id($User->id)->latest();
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('user-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.users.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a> <a href=' . route(request()->segment(1) . '.users.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a> <a role="button" class="btn bg-gradient-primary" href='.url(request()->segment(1) . '/admin-login-as-employee', (encrypt(@$data->id))).'>Login As ' .$data->name. '</a>';
                        }
                        $btn .= '</span>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } else {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" checked="" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        }
                    })
                    ->addColumn('image', function ($data) {
                        return '<a title="Click for View" data-lightbox="roadtrip" href="' . asset($data->image) . '"><img id="demo-test-gallery" class="border-radius-lg shadow demo-gallery" src="' . asset($data->image) . '" height="40px" width="40px"  />';
                    })


                    ->rawColumns(['image', 'action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.users.index'), 'name' => "Employee"],
                ['name' => 'List'],
            ];
            return view('backend.common.users.index', compact('breadcrumbs'));
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Helper::employeeCount(Auth::user()->id)<Auth::user()->staff_quantity) {
            $allroles = Role::whereuser_id(Auth::id())->select('id', 'name', 'user_id')->get();
            $data = array();
            for ($i = 0; $i < count($allroles); $i++) {
                $data[] = array('name' => rtrim($allroles[$i]->name, $allroles[$i]->admin_id), 'id' => $allroles[$i]->name);
            }
            $roles = collect($data)->pluck('name', 'id');

            return view('backend.common.users.create', compact('roles'));
        } else {
            Toastr::error('Please Update Your package', "Error");
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'shop_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:9|max:30|unique:users,phone',
            'password' => 'required|min:6|max:40',
            'roles' => 'required',
            'gender' => 'required',



        ]);

        $name = $request->name;
        $password = Hash::make($request->password);
        $user = new User();
        $user->name = $name;
        $user->shop_id = $request->shop_id;
        $user->status = $request->status;
        $user->admin_id = Auth::id();
        $user->user_type = 'Employee';
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->phone = $request->phone;
        if ($request->hasfile('image')) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8000',

            ]);


            if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/users/")) {
                mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/users/", 0777, true);
            }
            if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/users/thumbs/")) {
                mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/users/thumbs/", 0777, true);
            }

            $ex = $request->image->extension();
            $rand = uniqid(Generate::Slug(Str::limit($request->name, 40)));
            $name = $rand . "." . $ex;
            $request->image->move(storage_path('/app/public/files/shares/uploads/' . Auth::id().'/users'), $name, 60);

            $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/users/' . $name)->resize(35, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/users/thumbs/' . $name, 60);
            $user->image = url('storage/files/shares/uploads/'.Auth::id()).'/users/'. $name;
         
        }

        $user->ip_address = $request->ip();
        $user->password =  $password;
        $user->created_user_id =  Auth::id();
        $user->updated_user_id =  Auth::id();
        if ($user->save()) {
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->gender = $request->gender;
        }
        $user->assignRole($request->input('roles'));
        $profile->save();
        Toastr::success("User Created Successfully", "Success");
        return redirect()->route(request()->segment(1) . '.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userDetails = User::with('roles','shop','permissions')->findOrFail(decrypt($id));
        return view('backend.common.users.show', compact('userDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $allroles = Role::whereadmin_id(Auth::id())->select('name', 'admin_id')->get();

        $datas = array();
        for ($i = 0; $i < count($allroles); $i++) {
            $datas[] = array('name' => rtrim($allroles[$i]->name, $allroles[$i]->admin_id), 'adminid' => $allroles[$i]->name);
        }

        $Customroles = collect($datas)->pluck('name', 'adminid');


        $user = User::join('profiles', 'profiles.user_id', '=', 'users.id')->select('users.*', 'profiles.gender')->findOrFail(decrypt($id));
        $userRole = $user->roles->all();
        $data = array();
        for ($i = 0; $i < count($userRole); $i++) {
            $data[] = array('name' => rtrim($userRole[$i]->name, @Auth::user()->id), 'adminid' => $userRole[$i]->name);
        }

        $userRoles = collect($data)->pluck('adminid', 'adminid');

        return view('backend.common.users.edit', compact('user'))->with('roles', $Customroles)->with('userRole', $userRoles);;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'shop_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|min:9|max:30|unique:users,phone,' . $id,
            'roles' => 'required',
            'gender' => 'required',

        ]);

        $user = User::find($id);
        $user->shop_id = $request->shop_id;
        $name = $request->name;
        $user->name = $name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        if ($request->hasfile('image')) {

            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8000',

            ]);
            if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/users/")) {
                mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/users/", 0777, true);
            }
            if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/users/thumbs/")) {
                mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/users/thumbs/", 0777, true);
            }
          
            Storage::delete('/app/public/files/shares/uploads/'. Auth::id().'/user/'.$user->image);
            Storage::delete('/app/public/files/shares/uploads/'. Auth::id().'/user/thumbs/'.$user->image);
            
           
            $ex = $request->image->extension();
            $rand = uniqid(Generate::Slug(Str::limit($request->name, 40)));
            $name = $rand . "." . $ex;
            $request->image->move(storage_path('/app/public/files/shares/uploads/' . Auth::id().'/users'), $name, 60);

            $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/users/' . $name)->resize(35, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/users/thumbs/' . $name, 60);
            $user->image = url('storage/files/shares/uploads/'.Auth::id()).'/users/'. $name;
        }
        $user->ip_address = $request->ip();
        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'required|min:6|max:30',

            ]);
            $user->password = Hash::make($request->password);
        }

        $user->updated_user_id =  Auth::id();
        if ($user->save()) {
            $profile = Profile::whereuser_id($user->id)->first();
            $profile->gender = $request->gender;
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
            $profile->save();
            Toastr::success("User Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.users.index');
        } else {
            Toastr::warning("User Update Fail", "Success");
            return redirect()->route(request()->segment(1) . '.users.index');
        }
    }
    public function updateStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        if ($user->save()) {
            return 1;
        }
        return 0;
    }
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
    public function loginAsEmployee($id)
    {
        User::find(Auth::id())->update(array('remember_token' => null));
        Auth::logout();
        $user=User::find(decrypt($id));
        Auth::login($user);
        return back();
    }
}
