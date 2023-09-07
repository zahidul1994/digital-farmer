<?php
namespace App\Http\Controllers\Common;
use PDF;
use App\Helpers\Helper;
use App\Models\Supplier;
use App\Models\ShopCurrentStock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Validation\Rule;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ShopCurrentStockController extends Controller
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

        $this->middleware('permission:shop-current-stock-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:shop-current-stock-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:shop-current-stock-edit', ['only' => ['edit', 'update','updateStatus']]);
        $this->middleware('permission:shop-current-stock-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
       
        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = ShopCurrentStock::with('admin','shop','product')->latest();
            } elseif($User->user_type == 'Admin') {
                $data = ShopCurrentStock::with('admin','shop','product')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = ShopCurrentStock::with('admin','shop','product')->whereadmin_id($this->User->admin_id)->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('stock', function ($data) {
                        $stockAlert= $data->product->low_quantity;
                        $stock= $data->stock_qty;
                        if( $stockAlert<$stock){
                         return '<span class="badge badge-success badge-sm">' .  $stock . '</span>';
                        }else{
                         return '<span class="badge badge-danger badge-sm">' .  $stock . '</span>';
                        }
                       
                   
                     
                 })
                  
                   ->rawColumns(['stock'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.shop-current-stocks.index'), 'name' => "Shop Current Stock"],
                ['name' => 'List'],
            ];

            return view('backend.common.shop_current_stocks.index',compact('breadcrumbs'));
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
            
            $supplierdue = new ShopCurrentStock();
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
            Toastr::success("ShopCurrentStock Created Successfully", "Success");
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
                $data = ShopCurrentStock::whereadmin_id($User->id)->findOrFail(decrypt($id));
            } else {
                $data = ShopCurrentStock::findOrFail(decrypt($id));
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
                $data = ShopCurrentStock::findOrFail(decrypt($id));
                $suppliers= Supplier::wherestatus(1)->select(
                    DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                    'id'
                )->pluck('name','id');
            }
            elseif ($User->user_type == 'Admin') {
                $data = ShopCurrentStock::whereadmin_id($User->id)->findOrFail(decrypt($id));
                $suppliers= Supplier::wherestatus(1)->whereadmin_id(Auth::id())->select(
                    DB::raw("CONCAT(supplier_name,' - ',supplier_phone) AS name"),
                    'id'
                )->pluck('name','id');
            }
             else {
                $data = ShopCurrentStock::whereadmin_id($User->admin_id)->findOrFail(decrypt($id));
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
            
            $supplierdue =ShopCurrentStock::find($id);
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
            Toastr::success("ShopCurrentStock Updated Successfully", "Success");
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
    public function destroy(ShopCurrentStock  $category)
    {
        //
    }
    public function supplierDuePdf($id)
    {
        
        //  try {
         
           $supplierDue = ShopCurrentStock::with('admin','user','supplier')->findOrFail(decrypt($id));
          
        
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
