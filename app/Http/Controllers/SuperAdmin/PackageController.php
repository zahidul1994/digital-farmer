<?php

namespace App\Http\Controllers\Superadmin;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PackageController extends Controller
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
            $data = Package::latest();

            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.packages.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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

                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.packages.index'), 'name' => "Package"],
                ['name' => 'List'],
            ];
            return view('backend.superadmin.packages.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.packages.index'), 'name' => "Package"],
            ['name' => 'Create'],
        ];
        return view('backend.superadmin.packages.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'package_name' => 'required|min:1|max:198|unique:packages',
                'price' => 'required|min:1|max:99999999',
                'duration' => 'required|min:1|max:99999999',
                'shop' => 'required|numeric|min:1|max:10',
                'description' => 'required|min:3|max:30000',
                'employee_manage' => 'required',
                'status' => 'required',
                'features.*' => 'required|min:3|max:30000',


            ],
            [
                'package_name.required' => "The Package name field is required",
                'package_name.min' => "The Package Minimum Length 1",
                'package_name.max' => "The Package Maximum Length 190",
                'employee_manage.required' => "The Product Store field is required",
                'employee_manage.min' => "The  Product Store Minimum Length 1",
                'employee_manage.max' => "The  Product Store Maximum Length 100000",


            ]
        );

        try {
            DB::beginTransaction();
            $package = new Package();
            $package->superadmin_id = $this->User->id;
            $package->package_name = $request->package_name;
            $package->price = $request->price;
            $package->shop = $request->shop;
            $package->slug = Generate::Slug($request->package_name);
            $package->duration = $request->duration;
            $package->employee_manage = $request->employee_manage;
            $package->features = json_encode($request->features);
            $package->description = $request->description;
            $package->save();
            DB::commit();

            Toastr::success("Package Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.packages.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package   $blog
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $User = $this->User;
            if ($User->user_type == 'Admin') {
                $data = Package::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Package::findOrFail($id);
            }
            return view('backend.common.packages.show')->with('slider', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function edit($id)
    {

        try {
            $package = Package::findOrFail(decrypt($id));
            $allfeatures = json_decode($package->features);

            $data = array();
            for ($i = 0; $i < count($allfeatures); $i++) {
                $data[] = array('feature' => $allfeatures[$i]);
            }
            $features = collect($data)->pluck('feature', 'feature');

            return view('backend.superadmin.packages.edit', compact('features'))->with('package', $package);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package   $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'package_name' => 'required|min:1|max:198|unique:packages,package_name,' . $id,
                'price' => 'required|min:1|max:99999999',
                'duration' => 'required|min:1|max:99999999',
                'description' => 'required|min:3|max:30000',
                'employee_manage' => 'required',
                'shop' => 'required|numeric|min:1|max:10',
                'status' => 'required',
                'features.*' => 'required|min:3|max:30000',
            ],
            [
                'package_name.required' => "The Package name field is required",
                'package_name.min' => "The Package Minimum Length 1",
                'package_name.max' => "The Package Maximum Length 190",
                'employee_manage.required' => "The Product Store field is required",
                'employee_manage.min' => "The  Product Store Minimum Length 1",
                'employee_manage.max' => "The  Product Store Maximum Length 100000",

            ]
        );

        try {
            DB::beginTransaction();
            $package = Package::findOrFail($id);
            $package->package_name = $request->package_name;
            $package->price = $request->price;
            $package->shop = $request->shop;
            $package->slug = Generate::Slug($request->package_name);
            $package->duration = $request->duration;
            $package->employee_manage = $request->employee_manage;
            $package->features = json_encode($request->features);
            $package->description = $request->description;
            $package->status = $request->status;
            $package->save();
            DB::commit();
            Toastr::success("Package Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.packages.index');
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
     * @param  \App\Models\Package   $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package  $blog)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $package = Package::findOrFail($request->id);
        $package->status = $request->status;
        if ($package->save()) {
            return 1;
        }
        return 0;
    }
}
