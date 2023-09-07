<?php

namespace App\Http\Controllers\Common;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SupplierDue;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{  
     private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
            if ($this->User->status == 0) {
                $request->session()->flush();
                Toastr::error('Your Account was Deactive Please Contact with Support Center', "Error");
                return redirect('login');
            }
            return $next($request);
        });

        $this->middleware('permission:supplier-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:supplier-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:supplier-edit', ['only' => ['edit', 'update','updateStatus']]);
        $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
       
        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Supplier::with('user','supplierdue')->latest();
            } elseif($User->user_type == 'Admin') {
                $data = Supplier::with('user','supplierdue')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = Supplier::with('user','supplierdue')->whereadmin_id($this->User->admin_id)->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('supplier-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.suppliers.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
                    ->addColumn('paid_total', function ($data) {
                       return $data->supplierdue->sum('paid');
                    })
                    ->addColumn('due_total', function ($data) {
                       return $data->purchase->sum('due');
                    })
                    ->addColumn('due_or_paid', function ($data) {
                        return ($data->supplierdue->sum('due'))-($data->supplierdue->sum('paid'));
                     })
                   ->rawColumns(['action', 'status','paid_total','due_total','due_or_paid'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.suppliers.index'), 'name' => "Supplier"],
                ['name' => 'List'],
            ];

            return view('backend.common.suppliers.index',compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.suppliers.index'), 'name' => "Supplier"],
            ['name' => 'Create'],
        ];
        return view('backend.common.suppliers.create',compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->User->user_type=='Admin'){
        $this->validate($request,
            [
            
                'supplier_phone' => 'required|min:9|max:30',
               'address' => 'required|min:1|max:198',
                'email' => 'email|max:198',
               'supplier_name' => ['required','min:1',
                'max:198', Rule::unique('suppliers')->where(function ($query){
                        return $query->where('admin_id', Auth::user()->id);
                    })
                ]               
                ],
            [
                'supplier_name.unique' => "The Supplier name field need to be unique",
                'supplier_name.required' => "The Supplier name field is required",
                'supplier_name.min' => "The Supplier name Minimum field length 1",
                'supplier_name.max' => "The Supplier name Maximum field length 100",
                'supplier_phone.required' => "The Supplier phone field is required",
                'supplier_phone.min' => "The Supplier phone Minimum field length 1",
                'supplier_phone.max' => "The Supplier phone Maximum field length 100",
                
                
                

            ]);
        }
        else{
            $this->validate($request,
            [
                'supplier_phone' => 'required|min:9|max:30',
                'address' => 'required|min:1|max:198',
                 'email' => 'email|max:198',
                'supplier_name' => ['required','min:1',
                'max:198', Rule::unique('suppliers')->where(function ($query) {
                        return $query->where('admin_id', Auth::user()->admin_id);
                    })
                ]               
                ],
            [
                'supplier_name.unique' => "The Supplier name field need to be unique",
                'supplier_name.required' => "The Supplier name field is required",
                'supplier_name.min' => "The Supplier name Minimum field length 1",
                'supplier_name.max' => "The Supplier name Maximum field length 100",
                'supplier_phone.required' => "The Supplier phone field is required",
                'supplier_phone.min' => "The Supplier phone Minimum field length 1",
                'supplier_phone.max' => "The Supplier phone Maximum field length 100",
                
                              

            ]
        );
        }

         try {
            DB::beginTransaction();
            $supplier = new Supplier();
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_phone = $request->supplier_phone;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->address = $request->address;
            $supplier->description = $request->description;
          if($this->User->user_type=="Admin"){
            $supplier->admin_id = $this->User->id;
           }else{
            $supplier->admin_id = $this->User->admin_id;
            $supplier->employee_id = $this->User->id;
           }
           $supplier->created_user_id = $this->User->id;
           $supplier->updated_user_id = $this->User->id;
           $supplier->status = $request->status;
           $supplier->save();
           $paid=$request->paid;
           $due=$request->due;
           if(!is_null($paid) || !is_null($due)){
            $supplierdue = new SupplierDue();
            $supplierdue->supplier_id = $supplier->id;
            $supplierdue->payment_type = 'Cash';
            $supplierdue->paid = $paid;
            $supplierdue->due = $due;
            $supplierdue->note = 'Supplier Previous Summations';
           if($this->User->user_type=="Admin"){
            $supplierdue->admin_id = $this->User->id;
            }else{
          $supplierdue->admin_id = $this->User->admin_id;
          $supplierdue->employee_id = $this->User->id;
            }
            $supplierdue->created_user_id = $this->User->id;
            $supplierdue->updated_user_id = $this->User->id;
            $supplierdue->save();
           }
            DB::commit();
            Toastr::success("Supplier Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.suppliers.index');
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
     * @param  \App\Models\Category   $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $User = $this->User;
            if ($User->user_type == 'Admin') {
                $data = Supplier::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Supplier::findOrFail($id);
            }
            return view('backend.common.categories.show')->with('slider', $data);
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
     * @param  \App\Models\Category   $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Supplier::findOrFail(decrypt($id));
            }
            elseif ($User->user_type == 'Admin') {
                $data = Supplier::whereadmin_id($User->id)->findOrFail(decrypt($id));
            }
             else {
                $data = Supplier::whereadmin_id($User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.suppliers.edit')->with('supplier', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    { if($this->User->user_type=='Admin'){
        $this->validate($request,
            [
                'supplier_phone' => 'required|min:9|max:30',
               'address' => 'required|min:1|max:198',
                'email' => 'email|max:198',
               'status' => 'required|min:0|max:100',
                'supplier_name' => ['required','min:1',
                'max:198', Rule::unique('suppliers')->ignore($id, 'id')->where(function ($query){
                        return $query->where('admin_id', Auth::user()->id);
                    })
                ]               
                ],
            [
                'supplier_name.unique' => "The Supplier name field need to be unique",
                'supplier_name.required' => "The Supplier name field is required",
                'supplier_name.min' => "The Supplier name Minimum field length 1",
                'supplier_name.max' => "The Supplier name Maximum field length 100",
                'supplier_phone.required' => "The Supplier phone field is required",
                'supplier_phone.min' => "The Supplier phone Minimum field length 1",
                'supplier_phone.max' => "The Supplier phone Maximum field length 100",
                
                

            ]);
        }
        else{
            $this->validate($request,
            [
                'supplier_phone' => 'required|min:9|max:30',
               'address' => 'required|min:1|max:198',
                'email' => 'email|max:198',
                'supplier_name' => ['required','min:1',
                'max:198', Rule::unique('suppliers')->ignore($id, 'id')->where(function ($query) {
                        return $query->where('admin_id', Auth::user()->admin_id);
                    })
                ]               
                ],
            [
                'supplier_name.unique' => "The Supplier name field need to be unique",
                'supplier_name.required' => "The Supplier name field is required",
                'supplier_name.min' => "The Supplier name Minimum field length 1",
                'supplier_name.max' => "The Supplier name Maximum field length 100",
                'supplier_phone.required' => "The Supplier phone field is required",
                'supplier_phone.min' => "The Supplier phone Minimum field length 1",
                'supplier_phone.max' => "The Supplier phone Maximum field length 100",
                
            ]
        );
        }

        try {
            DB::beginTransaction();
            $supplier = Supplier::find($id);
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_phone = $request->supplier_phone;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->address = $request->address;
            $supplier->description = $request->description;
           if($this->User->user_type=="Admin"){
            $supplier->admin_id = $this->User->id;
           }else{
            $supplier->admin_id = $this->User->admin_id;
            $supplier->employee_id = $this->User->id;
           }
           $supplier->updated_user_id = $this->User->id;
           $supplier->status = $request->status;
           $supplier->save();
            DB::commit();
           Toastr::success("Supplier Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.suppliers.index');
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
     * @param  \App\Models\Category   $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $category = Supplier::findOrFail($request->id);
        $category->status = $request->status;
        $category->updated_user_id = Auth::id();
        if ($category->save()) {
            return 1;
        }
        return 0;
    }
}
