<?php
namespace App\Http\Controllers\Superadmin;
use App\Helpers\Helper;
use App\Models\Profile;
use App\Models\District;
use App\Models\Division;
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
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DistrictController extends Controller
{
   
    function __construct()
    {

       

    }
    public function index(Request $request)
    {
        
         $info = District::latest()->first();
        if (is_null($info)) {
            Helper::districtMigrate();
        }

        try {
            
            $data = District::with('division')->latest();
            if ($request->ajax()) {
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '';
                        $btn = '<a href=' . route(request()->segment(1) . '.divisions.edit', (encrypt($data->id))) . ' role="button" class="btn bg-gradient-info"  style="margin-right: 5px"><i class="fa fa-edit"></i></a>';
                        return $btn;
                    })
                   
                  

                    ->rawColumns([ 'action'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.divisions.index'), 'name' => "Admin"],
                ['name' => 'List'],
            ];
            return view('backend.superadmin.setting.location.districts.index', compact('breadcrumbs'));
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
        $allroles = Role::whereadmin_id(Auth::id())->select('id', 'name', 'admin_id')->get();
        $data = array();
        for ($i = 0; $i < count($allroles); $i++) {
            $data[] = array('name' => rtrim($allroles[$i]->name, $allroles[$i]->admin_id), 'id' => $allroles[$i]->name);
        }
        $Customroles = collect($data)->pluck('name', 'id');
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.divisions.index'), 'name' => "Admin List"],
            ['name' => 'Create'],
        ];

       
        return view('backend.superadmin.divisions.create', compact('breadcrumbs'))->with('roles', $Customroles);

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
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:9|max:30|unique:users,phone',
            'password' => 'required|min:6|max:40',
            'roles' => 'required',
            'image' => 'required',
            'gender' => 'required',
          

        ]);
        $referId = IdGenerator::generate(['table' => 'users', 'field' => 'username', 'length' => 8, 'prefix' => date('Y'), 'reset_on_prefix_change' => true]);
        $name = $request->name;
        $password = Hash::make($request->password);
       
        $division = new Division();
        $division->name = $name;
        $division->user_type = "Admin";
        $division->email = $request->email;
        $division->username = $referId;
        $division->email_verified_at = now();
        $division->phone = $request->phone;
        $division->status = $request->status;
        $division->image = $request->image;
        $division->ip_address = $request->ip();
        $division->password = $password;
        $division->created_user_id = Auth::id();
        $division->updated_user_id = Auth::id();
        if ($division->save()) {
            $profile = new Profile();
            $profile->user_id = $division->id;
            $profile->gender = $request->gender;
           
        }
      
        $division->assignRole($request->input('roles'));
        $profile->save();
        Toastr::success("Division Created Successfully", "Success");
        return redirect()->route(request()->segment(1) . '.divisions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $division = Division::find($id);
        return view('backend.superadmin.divisions.show', compact('division'));
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


        $admin = Division::join('profiles', 'profiles.user_id', '=', 'users.id')->select('users.*', 'profiles.gender', 'profiles.country', 'profiles.company_name', 'profiles.company_logo', 'profiles.refer_code', 'profiles.package_start_date', 'profiles.package_end_date', 'profiles.company_address', 'profiles.owner_name', 'profiles.web_address', 'profiles.description')->findOrFail(decrypt($id));
        $userRole = $admin->roles->all();
        $data = array();
        for ($i = 0; $i < count($userRole); $i++) {
            $data[] = array('name' => rtrim($userRole[$i]->name, @Auth::division()->id), 'adminid' => $userRole[$i]->name);
        }

        $userRoles=collect($data)->pluck('adminid','adminid');


        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.divisions.index'), 'name' => "Admin List"],
            ['name' => 'Edit'],
        ];
        $package = Package::pluck('package_name', 'package_name');
        return view('backend.superadmin.divisions.edit', compact('admin', 'breadcrumbs', 'package'))->with('roles', $Customroles)->with('userRole', $userRoles);
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
            'package' => 'required',
            'package_start_date' => 'required',
            'gender' => 'required',
            'company_name' => 'required',
            'company_address' => 'required',

        ]);

        $division = Division::find($id);
        $package = $request->package;
        $name = $request->name;
        $date = $request->package_start_date;
        $division->name = $name;
        $division->package = $package;
        $division->email = $request->email;
        $division->phone = $request->phone;
        $division->status = $request->status;
        $division->image = $request->image;
        $division->ip_address = $request->ip();
        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'required|min:6|max:40',

            ]);
            $division->password = Hash::make($request->password);
        }

        $division->updated_user_id = Auth::id();
        if ($division->save()) {
            $profile = Profile::whereuser_id($division->id)->firstOrFail();
            $profile->gender = $request->gender;
            $profile->company_name = $request->company_name;
            $profile->refer_code = $request->refer_code;
            $profile->company_address = $request->company_address;
            $profile->web_address = $request->web_address;
            $profile->company_logo = $request->company_logo;
            $profile->package_start_date = $date;
            $profile->package_end_date = Helper::packageEndDate($package, $date);
            $profile->description = $request->description;
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $division->assignRole($request->input('roles'));
             $profile->save();
            Toastr::success("Admin Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.divisions.index');

        } else {
            Toastr::warning("Admin Update Fail", "Success");
            return redirect()->route(request()->segment(1) . '.divisions.index');
        }
    }
    public function updateStatus(Request $request)
    {
        $division = Division::findOrFail($request->id);
        $division->status = $request->status;
        if ($division->save()) {
            return 1;
        }
        return 0;
    }
    public function destroy($id)
    {
        Division::find($id)->delete();
        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted successfully');
    }

    public function loginAsAdmin($id)
    {
        Division::find(Auth::id())->update(array('remember_token' => null));
        Auth::logout();
        $division=Division::find(decrypt($id));
        Auth::login($division);
        return back();
    }
}