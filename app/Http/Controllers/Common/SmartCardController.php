<?php

namespace App\Http\Controllers\Common;

use App\Models\SmartCard;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmartCardController extends Controller
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

        $this->middleware('permission:smart-card-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:smart-card-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:smart-card-edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:smart-card-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
  try {

        $User = $this->User;

        if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
            $data = SmartCard::latest();
        } elseif ($User->user_type == 'Mso') {

            $data =  SmartCard::wheremso_id(Auth::id())->latest();
        } else {

            $data =  SmartCard::wheremso_id(Auth::user()->mso_id)->latest();
        }
        if ($request->ajax()) {

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) use ($User) {
                    $btn = '<a href=' . route(request()->segment(1) . '.smart-card.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                    $btn .= '<a href=' . route(request()->segment(1) . '.smart-card.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
            ['link' => route(request()->segment(1) . '.smart-card.index'), 'name' => "Stp Provider"],
            ['name' => 'Stp Provider List'],
        ];
        return view('backend.common.smart_card.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.smart-card.index'), 'name' => "Set Top Box"],
            ['name' => 'Create'],
        ];
        return view('backend.common.smart_card.create', compact('breadcrumbs'));
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
                'version' => 'required|min:1|max:190',
                'keyword' => 'required|min:1|max:190',
                'status' => 'required',
                 'name' => [
                    'required', 'min:1',
                    'max:190', Rule::unique('smart_cards')->where(function ($query) use ($msoId) {
                        return $query->where('mso_id', $msoId);
                    })
                ],

            ]
        );
         try {
            DB::beginTransaction();
            $sc = new SmartCard();
            $sc->mso_id = $msoId;
            $sc->staff_id = $staffId;
            $sc->name = $request->name;
            $sc->version = $request->version;
            $sc->keyword = $request->keyword;
            $sc->status = $request->status;
            $sc->created_user_id = $this->User->id;
            $sc->updated_user_id = $this->User->id;
            $sc->save();
            DB::commit();
            if ($request->has('saveandback')) {
                Toastr::success("Smart Card Created Successfully  Done. Add  Another Smart Card", "Success");
                return redirect()->back();
            } else {
                Toastr::success("Smart Card Created Successfully", "Success");
                return redirect()->route(request()->segment(1) . '.smart-card.index');
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
     * @param  \App\Models\Category   $sc
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.smart-card.index'), 'name' => "sc Provider"],
            ['name' => 'Show'],
        ];
        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
                $data = SmartCard::findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Mso') {
                $data = SmartCard::wheremso_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = SmartCard::wheremso_id($this->User->mso_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.smart_card.show', compact('breadcrumbs'))->with('sc', $data);
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category   $sc
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
                $data = SmartCard::findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Mso') {
                $data = SmartCard::wheremso_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = SmartCard::wheremso_id($this->User->mso_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.smart_card.edit')->with('sc', $data);
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
                'version' => 'required|min:1|max:190',
                'keyword' => 'required|min:1|max:190',
                'status' => 'required',
                'name' => [
                    'required', 'min:1',
                    'max:190', Rule::unique('smart_cards')->ignore($id, 'id')->where(function ($query) use ($msoId) {
                        return $query->where('mso_id', $msoId);
                    })
                ]

            ]
        );
         try {
            DB::beginTransaction();
            $sc = SmartCard::find($id);
            $sc->mso_id = $msoId;
            $sc->staff_id = $staffId;
            $sc->name = $request->name;
            $sc->version = $request->version;
            $sc->keyword = $request->keyword;
            $sc->status = $request->status;
            $sc->updated_user_id = $this->User->id;
            $sc->save();
            DB::commit();
            Toastr::success("Smart Card Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.smart-card.index');
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
     * @param  \App\Models\Category   $sc
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmartCard  $sc)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $sc = SmartCard::findOrFail($request->id);
        $sc->status = $request->status;
        if ($sc->save()) {
            return 1;
        }
        return 0;
    }
}
