<?php

namespace App\Http\Controllers\Common;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    private $User;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $User = $this->User;
                $roles = Role::whereuser_id(Auth::id())->orderBy('id', 'DESC')->latest();
                if ($request->ajax()) {
                return Datatables::of($roles)->addIndexColumn()
                    ->addColumn('action', function ($roles) use ($User) {
                        $btn = '';
                        if ($User->can('role-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.roles.edit', encrypt($roles->id)) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        }
                        $btn .= '</span>';
                        return $btn;
                    })
                    ->addColumn('permission', function ($data) {
                        $info = [];
                        foreach ($data->permissions as $v) {
                            $info[] = '<span class="badge badge-primary">' . $v->name . '</span> ';

                        }
                        return $info;

                    })
                    ->addColumn('name', function ($data) {

                        return '<span class="badge badge-success">' . rtrim($data->name, $data->admin_id) . '</span> ';

                    })
                    ->rawColumns(['action', 'permission', 'name'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.roles.index'), 'name' => "Role"],
                ['name' => 'List'],
            ];
            return view('backend.common.roles.index', compact('breadcrumbs'));
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

        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.roles.index'), 'name' => "Role List"],
            ['name' => 'Create']
        ];
        $permission = Permission::get();
        return view('backend.common.roles.create', compact('permission', 'breadcrumbs'));
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
            'permission.*' => 'required|min:1',
            'name' => ['required','min:1',
                'max:60', Rule::unique('roles')->where(function ($query)use($request) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
        ]);
        try {
            DB::beginTransaction();
            $name=$request->input('name') . Auth::user()->id;
            $check=Role::wherename($name)->first();
            if($check){
                return redirect()->back()
                ->withErrors([
                'name' => 'Name Has Already Taken'
            ]);
            }
       
        $role = Role::create(['user_id' => Auth::id(), 'name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($request->input('permission'));
        DB::commit();
        Toastr::success('Role Created Successfully ');
        return redirect()->route(request()->segment(1) . '.roles.index');
      } catch (\Exception $e) {
        DB::rollBack();
        $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
        Toastr::error($response['message'], "Error");
        return back();
    }
    }

    public function edit($id)
    {
        if ($this->User->user_type == 'Superadmin') {
            $role = Role::find(decrypt($id));
        } else {
            $role = Role::whereuser_id(Auth::id())->find(decrypt($id));
        }

        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", decrypt($id))

            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')

            ->all();
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.roles.index'), 'name' => "Role List"],
            ['name' => 'Edit']
        ];

        return view('backend.common.roles.edit', compact('role', 'permission', 'rolePermissions', 'breadcrumbs'));
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
            'permission.*' => 'required',
            'name' => [
                'required',
                'min:1',
                'max:60', Rule::unique('roles')->ignore($id, 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],

        ]);
        try {
            DB::beginTransaction();
            $name=$request->input('name') . Auth::user()->id;
        $check=Role::where('id','!=',$id)->wherename($name)->first();
        if($check){
            return redirect()->back()
            ->withErrors([
            'name' => 'Name Has Already Taken'
        ]);
        }
        $role = Role::find($id);
        $role->name = $request->name . Auth::user()->id;
        $role->save();
        DB::commit();
        $role->syncPermissions($request->input('permission'));
        Toastr::success('Role Created Successfully ');
        return redirect()->route(request()->segment(1) . '.roles.index');
    } catch (\Exception $e) {
        DB::rollBack();
        $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
        Toastr::error($response['message'], "Error");
        return back();
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}