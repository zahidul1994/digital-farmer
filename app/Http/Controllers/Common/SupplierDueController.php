<?php
namespace App\Http\Controllers\Common;
use PDF;
use App\Helpers\Helper;
use App\Models\Supplier;
use App\Models\SupplierDue;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class SupplierDueController extends Controller
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

        $this->middleware('permission:supplier-due-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:supplier-due-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:supplier-due-edit', ['only' => ['edit', 'update','updateStatus']]);
        $this->middleware('permission:supplier-due-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
       
        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = SupplierDue::with('user','supplier')->latest();
            } elseif($User->user_type == 'Admin') {
                $data = SupplierDue::with('user','supplier')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = SupplierDue::with('user','supplier')->whereadmin_id($this->User->admin_id)->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('supplier-due-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.supplier-due.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        }
                        $btn .= '<a href=' . route(request()->segment(1) . '.supplier-due.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a> <a href=' . route(request()->segment(1) . '.supplierDuePdf', (encrypt($data->id))) . ' class="btn btn-primary btn-sm waves-effect" style="margin-left: 5px" target="_blank"><i class="fas fa-file-pdf"></i></a>';
                        $btn .= '</span>';
                        return $btn;
                    })                                     
                 
                  
                   ->rawColumns(['action'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.supplier-due.index'), 'name' => "Supplier Due"],
                ['name' => 'List'],
            ];

            return view('backend.common.supplier_dues.index',compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.supplier-due.index'), 'name' => "Supplier Due"],
            ['name' => 'Create'],
        ];
        if(Auth::user()->user_type=='SuperAdmin'){
           $suppliers= Supplier::wherestatus(1)->select(
            DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
            'id'
        )->pluck('name','id');
        }
        elseif(Auth::user()->user_type=='Admin'){
            $suppliers= Supplier::wherestatus(1)->whereadmin_id(Auth::id())->select(
                DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                'id'
            )->pluck('name','id');
        }
        else{
            $suppliers= Supplier::wherestatus(1)->whereadmin_id(Auth::user()->admin_id)->select(
                DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                'id'
            )->pluck('name','id');
        }
        return view('backend.common.supplier_dues.create',compact('breadcrumbs','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $this->validate($request,[
                'supplier_id' => 'required',
                'payment_method' => 'required',
               'paid' => 'required|numeric|between:1,99999999',
                'note' => 'max:330',
                
            ]);
        
       

         try {
            DB::beginTransaction();
            
            $supplierdue = new SupplierDue();
            $supplierdue->supplier_id = $request->supplier_id;
            $supplierdue->paid = $request->paid;
            $supplierdue->payment_method = $request->payment_method;
            $supplierdue->phone_number = $request->phone_number;
            $supplierdue->transaction_number = $request->transaction_number;
          if($this->User->user_type=="Admin"){
            $supplierdue->admin_id = $this->User->id;
            $prefix = Str::limit(Helper::getProfile(Auth::id())->company_name, 3, '');
           }else{
            $supplierdue->admin_id = $this->User->admin_id;
            $supplierdue->employee_id = $this->User->id;
            $prefix = Str::limit(Helper::getProfile(Auth::user()->admin_id)->company_name, 3, '');
           }
           $supplierdue->invoice_no = IdGenerator::generate(['table' => 'supplier_dues', 'field' => 'invoice_no', 'length' => 8, 'prefix' => $prefix, 'reset_on_prefix_change' => true]);
           $supplierdue->created_user_id = $this->User->id;
           $supplierdue->updated_user_id = $this->User->id;
           $supplierdue->bank_name = $request->bank_name;
           $supplierdue->bank_account_number = $request->bank_account_number;
           $supplierdue->note = $request->note;
           $supplierdue->save();
           
            DB::commit();
            Toastr::success("SupplierDue Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.supplier-due.index');
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
                $data = SupplierDue::whereadmin_id($User->id)->findOrFail(decrypt($id));
            } else {
                $data = SupplierDue::findOrFail(decrypt($id));
            }
            return view('backend.common.supplier_dues.show')->with('supplierDueDetails', $data);
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
                $data = SupplierDue::findOrFail(decrypt($id));
                $suppliers= Supplier::wherestatus(1)->select(
                    DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                    'id'
                )->pluck('name','id');
            }
            elseif ($User->user_type == 'Admin') {
                $data = SupplierDue::whereadmin_id($User->id)->findOrFail(decrypt($id));
                $suppliers= Supplier::wherestatus(1)->whereadmin_id(Auth::id())->select(
                    DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                    'id'
                )->pluck('name','id');
            }
             else {
                $data = SupplierDue::whereadmin_id($User->admin_id)->findOrFail(decrypt($id));
                $suppliers= Supplier::wherestatus(1)->whereadmin_id(Auth::user()->admin_id)->select(
                    DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                    'id'
                )->pluck('name','id');
            }
        
            return view('backend.common.supplier_dues.edit',compact('suppliers'))->with('supplier', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id){
      
        $this->validate($request,[
                'supplier_id' => 'required',
                'payment_method' => 'required',
               'paid' => 'required|numeric|between:1,99999999',
                'note' => 'max:330',
                
            ]);
        
       

         try {
            DB::beginTransaction();
            
            $supplierdue =SupplierDue::find($id);
            $supplierdue->supplier_id = $request->supplier_id;
            $supplierdue->paid = $request->paid;
            $supplierdue->payment_method = $request->payment_method;
            $supplierdue->phone_number = $request->phone_number;
            $supplierdue->transaction_number = $request->transaction_number;
          if($this->User->user_type=="Admin"){
            $supplierdue->admin_id = $this->User->id;
           }else{
            $supplierdue->admin_id = $this->User->admin_id;
            $supplierdue->employee_id = $this->User->id;
           }
          
           $supplierdue->updated_user_id = $this->User->id;
           $supplierdue->bank_name = $request->bank_name;
           $supplierdue->bank_account_number = $request->bank_account_number;
           $supplierdue->note = $request->note;
           $supplierdue->save();
           
            DB::commit();
            Toastr::success("SupplierDue Updated Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.supplier-due.index');
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
    public function destroy(SupplierDue  $category)
    {
        //
    }
    public function supplierDuePdf($id)
    {
        
        //  try {
         
           $supplierDue = SupplierDue::with('admin','user','supplier')->findOrFail(decrypt($id));
          
        
         $pdf = PDF::loadView('backend.common.supplier_dues.pdf',compact('supplierDue'));
         return $pdf->stream('supplier-due_' . now() . '.pdf');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
        //     Toastr::error($response['message'], "Error");
        //     return back();
        // }
    }
}
