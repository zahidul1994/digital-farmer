<?php

namespace App\Http\Controllers\Common;

use App\Models\SetTopBox;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SetTopBoxController extends Controller
{
    private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
            if ($this->User->status == 0) {
                $request->session()->flush();
                Toastr::error('Your Account was Deactivate Please Contact with Support Center', "Error");
                return redirect('login');
            }
            return $next($request);
        });

        $this->middleware('permission:set-top-box-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:set-top-box-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:set-top-box-edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:set-top-box-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
  try {

        $User = $this->User;

        if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
            $data = SetTopBox::latest();
        } elseif ($User->user_type == 'Mso') {

            $data =  SetTopBox::wheremso_id(Auth::id())->latest();
        } else {

            $data =  SetTopBox::wheremso_id(Auth::user()->mso_id)->latest();
        }
        if ($request->ajax()) {

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) use ($User) {
                    $btn = '<a href=' . route(request()->segment(1) . '.set-top-box.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                    $btn .= '<a href=' . route(request()->segment(1) . '.set-top-box.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
            ['link' => route(request()->segment(1) . '.set-top-box.index'), 'name' => "Stp Provider"],
            ['name' => 'Stp Provider List'],
        ];
        return view('backend.common.set_top_box.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.set-top-box.index'), 'name' => "Set Top Box"],
            ['name' => 'Create'],
        ];
        return view('backend.common.set_top_box.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if (Auth::user()->user_type == "Mso") {
            $msoId = Auth::id();
            $staffId = NULL;
        } else {
            $msoId = Auth::user()->mso_id;
            $staffId = Auth::id();
        }

        $this->validate(
            $request,
            [

                'hw_version' => 'max:198',
                'model_name' => 'required|min:1|max:190',
                'keyword' => 'required|min:1|max:190',
                'status' => 'required',
                'is_sc_builtin' => 'required',
                'vendor_name' => [
                    'required', 'min:1',
                    'max:190', Rule::unique('set_top_boxes')->where(function ($query) use ($msoId) {
                        return $query->where('mso_id', $msoId);
                    })
                ],

            ]
        );
         try {
            DB::beginTransaction();
            $stp = new SetTopBox();
            $stp->mso_id = $msoId;
            $stp->staff_id = $staffId;
            $stp->vendor_name = $request->vendor_name;
            $stp->hw_version = $request->hw_version;
            $stp->is_sc_builtin = $request->is_sc_builtin;
           $stp->keyword = $request->keyword;
            $stp->model_name = $request->model_name;
            if($request->has('is_same_as_sc')){
                $stp->is_same_as_sc = 1;
            }           
            $stp->ca_vendor_name = $request->ca_vendor_name;
            $stp->ca_version = $request->ca_version;
            $stp->status = $request->status;
            $stp->created_user_id = $this->User->id;
            $stp->updated_user_id = $this->User->id;
            $stp->save();
            DB::commit();
            if ($request->has('saveandback')) {
                Toastr::success("STB Created Successfully  Done. Add  Another STB", "Success");
                return redirect()->back();
            } else {
                Toastr::success("STB Created Successfully", "Success");
                return redirect()->route(request()->segment(1) . '.set-top-box.index');
            }
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
     * @param  \App\Models\Category   $stp
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.set-top-box.index'), 'name' => "stp Provider"],
            ['name' => 'Show'],
        ];
        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
                $data = SetTopBox::findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Mso') {
                $data = SetTopBox::wheremso_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = SetTopBox::wheremso_id($this->User->mso_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.set_top_box.show', compact('breadcrumbs'))->with('stp', $data);
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category   $stp
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
                $data = SetTopBox::findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Mso') {
                $data = SetTopBox::wheremso_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = SetTopBox::wheremso_id($this->User->mso_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.set_top_box.edit')->with('stp', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    {
        
        if (Auth::user()->user_type == "Mso") {
            $msoId = Auth::id();
            $staffId = NULL;
        } else {
            $msoId = Auth::user()->mso_id;
            $staffId = Auth::id();
        }

        $this->validate(
            $request,
            [
                'hw_version' => 'max:198',
                'model_name' => 'required|min:1|max:190',
                'keyword' => 'required|min:1|max:190',
                'status' => 'required',
                'is_sc_builtin' => 'required',
                'vendor_name' => [
                    'required', 'min:1',
                    'max:190', Rule::unique('set_top_boxes')->ignore($id, 'id')->where(function ($query) use ($msoId) {
                        return $query->where('mso_id', $msoId);
                    })
                ]

            ]
        );
         try {
            DB::beginTransaction();
            $stp = SetTopBox::find($id);
            $stp->vendor_name = $request->vendor_name;
            $stp->hw_version = $request->hw_version;
            $stp->keyword = $request->keyword;
            $stp->model_name = $request->model_name;
            $stp->is_sc_builtin = $request->is_sc_builtin;
            $stp->ca_vendor_name = $request->ca_vendor_name;
            $stp->ca_version = $request->ca_version;
            $stp->status = $request->status;
            if($request->has('is_same_as_sc')){
                $stp->is_same_as_sc = 1;
            }else{
                $stp->is_same_as_sc = 0;
            }        
            $stp->updated_user_id = $this->User->id;
            $stp->save();
            DB::commit();
            Toastr::success("SetTopBox Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.set-top-box.index');
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
     * @param  \App\Models\Category   $stp
     * @return \Illuminate\Http\Response
     */
    public function destroy(SetTopBox  $stp)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $stp = SetTopBox::findOrFail($request->id);
        $stp->status = $request->status;
        if ($stp->save()) {
            return 1;
        }
        return 0;
    }
}
