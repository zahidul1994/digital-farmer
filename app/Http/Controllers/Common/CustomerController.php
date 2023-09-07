<?php

namespace App\Http\Controllers\Common;
use App\Models\Customer;
use App\Models\CustomerDue;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
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

        $this->middleware('permission:customer-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update','updateStatus']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
       
        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Customer::with('user','customerdue')->latest();
            } elseif($User->user_type == 'Admin') {
                $data = Customer::with('user','customerdue')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = Customer::with('user','customerdue')->whereadmin_id($this->User->admin_id)->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('customer-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.customers.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
                       return $data->customerdue->sum('paid');
                    })
                    ->addColumn('due_total', function ($data) {
                       return $data->customerdue->sum('due');
                    })
                    ->addColumn('due_or_paid', function ($data) {
                        return ($data->customerdue->sum('due'))-($data->customerdue->sum('paid'));
                     })
                   ->rawColumns(['action', 'status','paid_total','due_total','due_or_paid'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.customers.index'), 'name' => "Customer"],
                ['name' => 'List'],
            ];

            return view('backend.common.customers.index',compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.customers.index'), 'name' => "Customer"],
            ['name' => 'Create'],
        ];
        return view('backend.common.customers.create',compact('breadcrumbs'));
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
      $adminId=Auth::id();
        }
        else{
            $adminId=Auth::user()->admin_id;
    
        }
        $this->validate($request,
        [
        
            'customer_name' => 'required|min:1|max:30',
           'address' => 'required|min:1|max:198',
           'discount' => 'required',
            'customer_email' => 'email|max:198',
           'customer_phone' => ['required','min:9',
            'max:30.', Rule::unique('customers')->where(function ($query)use($adminId){
                    return $query->where('admin_id', $adminId);
                })
            ]               
            ],
        [
            
            'customer_phone.unique' => "The Customer phone field need to be unique",
            'customer_phone.required' => "The Customer phone field is required",
            'customer_phone.min' => "The Customer phone Minimum field length 9",
            'customer_phone.max' => "The Customer phone Maximum field length 30",
            'customer_name.required' => "The Customer name field is required",
            'customer_email.email' => "The Customer email must be a valid email",
            'customer_email.max' => "The Customer email Maximum field length 190",

        ]);
          try {
            DB::beginTransaction();
            $customer = new Customer();
            $customer->customer_name = $request->customer_name;
            $customer->customer_phone = $request->customer_phone;
            $customer->customer_email = $request->customer_email;
            $customer->address = $request->address;
            $customer->discount = $request->discount;
            $customer->description = $request->description;
          if($this->User->user_type=="Admin"){
            $customer->admin_id = $this->User->id;
           }else{
            $customer->admin_id = $this->User->admin_id;
            $customer->employee_id = $this->User->id;
           }
           $customer->created_user_id = $this->User->id;
           $customer->updated_user_id = $this->User->id;
           $customer->status = $request->status;
           $customer->save();
           $paid=$request->paid;
           $due=$request->due;
           if(!is_null($paid) || !is_null($due)){
            $customerdue = new CustomerDue();
            $customerdue->customer_id = $customer->id;
            $customerdue->payment_type = 'Cash';
            $customerdue->paid = $paid;
            $customerdue->due = $due;
            $customerdue->note = 'Customer Previous Summations';
           if($this->User->user_type=="Admin"){
            $customerdue->admin_id = $this->User->id;
            }else{
          $customerdue->admin_id = $this->User->admin_id;
          $customerdue->employee_id = $this->User->id;
            }
            $customerdue->created_user_id = $this->User->id;
            $customerdue->updated_user_id = $this->User->id;
            $customerdue->save();
           }
            DB::commit();
            Toastr::success("Customer Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.customers.index');
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
                $data = Customer::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Customer::findOrFail($id);
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
                $data = Customer::findOrFail(decrypt($id));
            }
            elseif ($User->user_type == 'Admin') {
                $data = Customer::whereadmin_id($User->id)->findOrFail(decrypt($id));
            }
             else {
                $data = Customer::whereadmin_id($User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.customers.edit')->with('customer', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    { 
        if($this->User->user_type=='Admin'){
               $adminId=Auth::id();
              }
              else{
                  $adminId=Auth::user()->admin_id;
              }

              $this->validate($request,[
                  'customer_name' => 'required|min:1|max:30',
                 'address' => 'required|min:1|max:198',
                 'discount' => 'required',
                  'customer_email' => 'email|max:198',
                 'customer_phone' => ['required','min:9',
                  'max:198', Rule::unique('customers')->ignore($id, 'id')->where(function ($query)use($adminId){
                        return $query->where('admin_id',  $adminId);
                    })
                  ]               
                  ],
              [
                  
                  'customer_phone.unique' => "The Customer phone field need to be unique",
                  'customer_phone.required' => "The Customer phone field is required",
                  'customer_phone.min' => "The Customer phone Minimum field length 9",
                  'customer_phone.max' => "The Customer phone Maximum field length 30",
                  'customer_name.required' => "The Customer name field is required",
                  'customer_email.email' => "The Customer email must be a valid email",
                  'customer_email.max' => "The Customer email Maximum field length 190",
      
              ]);
        
        try {
            DB::beginTransaction();
            $customer = Customer::find($id);
            $customer->customer_name = $request->customer_name;
            $customer->customer_phone = $request->customer_phone;
            $customer->customer_email = $request->customer_email;
            $customer->address = $request->address;
            $customer->discount = $request->discount;
            $customer->description = $request->description;
           if($this->User->user_type=="Admin"){
            $customer->admin_id = $this->User->id;
           }else{
            $customer->admin_id = $this->User->admin_id;
            $customer->employee_id = $this->User->id;
           }
           $customer->updated_user_id = $this->User->id;
           $customer->status = $request->status;
           $customer->save();
            DB::commit();
           Toastr::success("Customer Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.customers.index');
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
    public function destroy(Customer  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $category = Customer::findOrFail($request->id);
        $category->status = $request->status;
        $category->updated_user_id = Auth::id();
        if ($category->save()) {
            return 1;
        }
        return 0;
    }
}
