<?php
namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class MsoController extends Controller
{
    private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
            return $next($request);
        });

    }
    public function index(Request $request)
    {

         try {
            $User = $this->User;
            $data = User::whereuser_type('Mso')->latest();
            if ($request->ajax()) {
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '';
                        $btn = '<a href=' . route(request()->segment(1) . '.mso.edit', (encrypt($data->id))) . ' role="button" class="btn bg-gradient-info"  style="margin-right: 5px"><i class="fa fa-edit"></i></a> <a role="button" class="btn bg-gradient-primary" href='.url(request()->segment(1) . '/admin-login-as-mso', (encrypt(@$data->id))).'>Login As ' .$data->name. '</a>';
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
                ['link' => route(request()->segment(1) . '.mso.index'), 'name' => "Mso"],
                ['name' => 'List'],
            ];
            return view('backend.admin.mso.index', compact('breadcrumbs'));
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
        $allroles = Role::whereuser_id(1)->whereNot('id',1)->select('id', 'name', 'user_id')->get();
        $data = array();
        for ($i = 0; $i < count($allroles); $i++) {
            $data[] = array('name' => rtrim($allroles[$i]->name, $allroles[$i]->user_id), 'id' => $allroles[$i]->name);
        }
        $Customroles = collect($data)->pluck('name', 'id');
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.mso.index'), 'name' => "Mso List"],
            ['name' => 'Create'],
        ];

       
        return view('backend.admin.mso.create', compact('breadcrumbs'))->with('roles', $Customroles);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:9|max:30|unique:users,phone',
             'roles' => 'required',
            'image' => 'required',
            'gender' => 'required',
          

        ]);
        $referId = IdGenerator::generate(['table' => 'users', 'field' => 'username', 'length' => 8, 'prefix' => date('Y'), 'reset_on_prefix_change' => true]);
        $name = $request->name;
        $password = Hash::make($request->password?:$request->phone);
       
        $user = new User();
        $user->name = $name;
        $user->user_type = "Mso";
        $user->email = $request->email;
        $user->username = $referId;
        $user->email_verified_at = now();
        $user->account_expire_date = Carbon::create(now())->addDays(90)->format("Y-m-d");
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->image = $request->image;
        $user->staff_quantity = $request->staff_quantity;
        $user->ip_address = $request->ip();
        $user->password = $password;
        $user->balance = 0;
        $user->admin_id = Auth::id();
        $user->created_user_id = Auth::id();
        $user->updated_user_id = Auth::id();
        if ($user->save()) {
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->gender = $request->gender;
            $profile->division = $request->division;
            $profile->district = $request->district;
            $profile->thana = $request->thana;
            $profile->area = $request->area;
            $profile->company_name = $request->company_name;
            $profile->company_address = $request->company_address;
            $profile->nid_number = $request->nid_number;
            $profile->gender = $request->gender;
           
        }
      
        $user->assignRole($request->input('roles'));
        $profile->save();
        Toastr::success("User Created Successfully", "Success");
        return redirect()->route(request()->segment(1) . '.mso.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('backend.superadmin.admins.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allroles = Role::whereuser_id(1)->whereNot('id',1)->select('name', 'user_id')->get();

        $datas = array();
        for ($i = 0; $i < count($allroles); $i++) {
            $datas[] = array('name' => rtrim($allroles[$i]->name, $allroles[$i]->user_id), 'adminid' => $allroles[$i]->name);
        }

        $Customroles = collect($datas)->pluck('name', 'adminid');


        $admin = User::join('profiles', 'profiles.user_id', '=', 'users.id')->findOrFail(decrypt($id));
       
        $userRole = $admin->roles->all();
        $data = array();
        for ($i = 0; $i < count($userRole); $i++) {
            $data[] = array('name' => rtrim($userRole[$i]->name, @Auth::user()->id), 'adminid' => $userRole[$i]->name);
        }

        $userRoles=collect($data)->pluck('adminid','adminid');


        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.mso.index'), 'name' => "Mso List"],
            ['name' => 'Edit'],
        ];
     
        return view('backend.admin.mso.edit', compact('admin', 'breadcrumbs'))->with('roles', $Customroles)->with('userRole', $userRoles);
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
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required|min:3|max:100',
            'phone' => 'required|min:9|max:30|unique:users,phone,' . $id,
            'roles' => 'required',
            'gender' => 'required',
            'company_name' => 'required',
            'company_address' => 'required',

        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->image = $request->image;
        $user->admin_id = Auth::id();
        $user->staff_quantity = $request->staff_quantity;
        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'required|min:6|max:40',

            ]);
            $user->password = Hash::make($request->password);
        }

        $user->updated_user_id = Auth::id();
        if ($user->save()) {
            $profile = Profile::whereuser_id($user->id)->firstOrFail();
            $profile->gender = $request->gender;
            $profile->division = $request->division;
            $profile->district = $request->district;
            $profile->thana = $request->thana;
            $profile->area = $request->area;
            $profile->company_name = $request->company_name;
            $profile->company_address = $request->company_address;
            $profile->nid_number = $request->nid_number;
            $profile->gender = $request->gender;
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
             $profile->save();
            Toastr::success("Mso Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.mso.index');

        } else {
            Toastr::warning("Mso Update Fail", "Success");
            return redirect()->route(request()->segment(1) . '.mso.index');
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
        return redirect()->route('admins.index')
            ->with('success', 'User deleted successfully');
    }

    public function loginMso($id)
    {
        Session::put('adminId',encrypt(Auth::id()));
        User::find(Auth::id())->update(array('remember_token' => null));
        Auth::logout();
        $user=User::find(decrypt($id));
        Auth::login($user);
        return back();
    }
}