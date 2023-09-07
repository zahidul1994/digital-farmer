<?php

namespace App\Http\Controllers\Common;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ColorController extends Controller
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

    public function index(Request $request)
    {

        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Color::latest();
            } elseif($User->user_type == 'Admin') {
                $data = Color::whereIn('language', json_decode($this->User->language))->latest();
            } else {
                $data = Color::whereuser_id($User->id)->whereIn('language', json_decode($this->User->language))->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('cc-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.ccs.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
                    
                    ->rawColumns([ 'action', 'status'])
                    ->make(true);
            }

            return view('backend.common.cars.setup.ccs.index');
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
        
        return view('backend.common.cars.setup.ccs.create');
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
                'cc_name' => 'required|min:1|max:198|unique:ccs',
               
                
            ],
            [
                'cc_name.required' => "The Color name field is required",
                'cc_name.min' => "The Color Minimum Length 1",
                'cc_name.max' => "The Color Maximum Length 190",
                
                

            ]
        );

        try {
            DB::beginTransaction();
            $cc = new Color();
            $cc->user_id = $this->User->id;
            $cc->cc_name = $request->cc_name;
            $cc->slug = Generate::Slug($request->cc_name);
            $cc->save();
            DB::commit();

            Toastr::success("Color Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.ccs.index');
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
     * @param  \App\Models\Color   $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $User = $this->User;
            if ($User->user_type == 'Admin') {
                $data = Color::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Color::findOrFail($id);
            }
            return view('backend.common.cars.setup.ccs.show')->with('slider', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Color   $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Color::findOrFail(decrypt($id));
            }
            elseif ($User->user_type == 'Admin') {
                $data = Color::whereIn('language', json_decode($this->User->language))->findOrFail(decrypt($id));
            }
             else {
                $data = Color::whereuser_id($User->id)->findOrFail(decrypt($id));
            }
            return view('backend.common.cars.setup.ccs.edit')->with('cc', $data);
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
     * @param  \App\Models\Color   $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'cc_name' => 'required|min:1|max:198|unique:ccs,cc_name,' . $id,
                

            ],
            [
                'cc_name.required' => "The Color name field is required",
                'cc_name.min' => "The Color Minimum Length 1",
                'cc_name.max' => "The Color Maximum Length 190",

            ]
        );

        try {
            DB::beginTransaction();
            $cc = Color::find($id);
            $cc->user_id = $this->User->id;
            $cc->cc_name = $request->cc_name;
            $cc->slug = Generate::Slug($request->cc_name);
            $cc->save();
            DB::commit();

            Toastr::success("Color Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.ccs.index');
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
     * @param  \App\Models\Color   $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $cc = Color::findOrFail($request->id);
        $cc->status = $request->status;
        if ($cc->save()) {
            return 1;
        }
        return 0;
    }
}
