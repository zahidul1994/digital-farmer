<?php

namespace App\Http\Controllers\Common;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use App\Models\PurchaseDetails;
use App\Models\ShopCurrentStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

use App\Notifications\Usernotification;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class SaleController extends Controller
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

        $this->middleware('permission:sale-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:sale-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sale-edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:sale-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {


        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Sale::with('user', 'customer')->latest();
            } elseif ($User->user_type == 'Admin') {
                $data = Sale::with('user', 'customer')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = Sale::with('user', 'customer')->whereadmin_id(Auth::user()->admin_id)->whereemployee_id($User->id)->latest();
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.sales.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                        $btn .= '<a href=' . route(request()->segment(1) . '.sales.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->grand_total == $data->paid) {
                            return '<button data-bs-toggle="tooltip" data-bs-placement="top" title="Paid" data-container="body" data-animation="true" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-check" aria-hidden="true"></i></button>';
                        } elseif ($data->paid == 0) {
                            return '<button data-bs-toggle="tooltip" data-bs-placement="top" title="Due" data-container="body" class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-times" aria-hidden="true"></i></button>';
                        } else {
                            return '<button data-bs-toggle="tooltip" data-bs-placement="top" title="Partial" data-container="body" class="btn btn-icon-only btn-rounded btn-outline-dark mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-undo" aria-hidden="true"></i></button>';
                        }
                    })


                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.sales.index'), 'name' => "Sale"],
                ['name' => 'List'],
            ];
            return view('backend.common.sales.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.sales.index'), 'name' => "Sale"],
            ['name' => 'Create'],
        ];
        return view('backend.common.sales.create', compact('breadcrumbs'));
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
                'shop_id' => 'required|min:1|max:198',
                'supplier_id' => 'required|min:1|max:198',
                'payment_method' => 'required|min:1|max:300',
                'paid' => 'max:99999999',
                'total_amount' => 'required|numeric|between:1,99999999',
                'product_id.*' => 'required',
                'product_quantity.*' => 'required',
                'product_price.*' => 'required|numeric|between:1,99999999',
                'product_total_price' => 'required',

            ],

            [

                'shop_id.required' => "The Shop name field is required",
                'supplier_id.required' => "The Supplier name field is required",
                'payment_method.required' => "The Payment Method name field is required",
                'paid.max' => "The Sale name Maximum Length 190",
                'total_amount.required' => "The Total amount name field is required",
                'total_amount.min' => "The Total amount Minimum Length 1",
                'total_amount.max' => "The Total amount Maximum Length 99999999",
                'product_id.required' => "The  Product name field is required",
                'product_price.required' => "The  Product Price name field is required",
                'product_price.min' => "The Product Price Minimum Length 1",
                'product_price.max' => "The Product Price  Maximum Length 99999999",
                'product_quantity.required' => "The Product  Quantity name field is required",
                'product_total_price.required' => "The Product  Quantity name field is required",
            ]
        );

          try {
        DB::beginTransaction();
        $sale = new Sale();
        if (Auth::user()->user_type == 'Admin') {
            $sale->admin_id  = Auth::id();
            $prefix = Str::limit(Helper::getProfile(Auth::id())->company_name, 3, '');
        } else {
            $sale->admin_id  = Auth::user()->admin_id;
            $sale->employee_id  = Auth::id();
            $prefix = Str::limit(Helper::getProfile(Auth::user()->admin_id)->company_name, 3, '');
        }
        $sale->invoice_no = IdGenerator::generate(['table' => 'sales', 'field' => 'invoice_no', 'length' => 8, 'prefix' => $prefix, 'reset_on_prefix_change' => true]);
        $shop = $request->shop_id;
        $supplier = $request->supplier_id;
        $date = date('Y-m-d');
        $sale->shop_id = $shop;
        $sale->supplier_id = $supplier;
        $sale->date = $date;
        $sale->total_vat = $request->total_vat;
        $sale->reference = $request->reference;
        $sale->total_discount = $request->total_discount ?: 0;
        $sale->sub_total = ($request->total_amount) - (($sale->total_vat) + ($request->total_discount));
        $sale->payment_type_id = $request->payment_method;
        $sale->paid = $request->paid ?: 0;
        $sale->due = ($request->total_amount) - ($sale->paid);
        $sale->grand_total = $request->total_amount;
        $sale->description = $request->description;
        $sale->created_user_id = $this->User->id;
        $sale->updated_user_id = $this->User->id;
        $sale->save();
        if ($sale) {



            $purchaseProducts = $request->product_id;

            for ($i = 0; $i < count($purchaseProducts); $i++) {
                $productId = $request->product_id[$i];
                $price = $request->product_price[$i];
                $name = $request->product_name[$i];
                $qty = $request->product_quantity[$i];
                $total = $request->product_total_price[$i];
                $productVat = $request->product_vat[$i];
                $productVatAmount = $request->product_vat_amount[$i];
                $product = Product::find($productId);
                $purchaseDetail = new PurchaseDetails();
                $purchaseDetail->purchase_id = $sale->id;
                $purchaseDetail->product_id = $productId;
                $purchaseDetail->product_name = $name;
                $purchaseDetail->qty =  $qty;
                $purchaseDetail->average_purchase_price = $product->average_price;
                $purchaseDetail->purchase_price = $price;
                $purchaseDetail->vat_percent = $productVat;
                $purchaseDetail->vat_amount = $productVatAmount;
                $purchaseDetail->total_price = $total;
                $purchaseDetail->save();
                $checkShop = ShopCurrentStock::whereproduct_id($productId)->whereshop_id($shop)->first();
                if ($checkShop) {
                    $checkShop->increment('stock_qty', $qty);
                } else {
                    $checkShop = new ShopCurrentStock();
                    if (Auth::user()->user_type == 'Admin') {
                        $checkShop->admin_id = Auth::id();
                    } else {
                        $checkShop->admin_id = Auth::user()->admin_id;
                    }
                    $checkShop->shop_id = $shop;
                    $checkShop->product_id = $productId;
                    $checkShop->stock_qty = $qty;
                    $checkShop->save();
                }
                $average_purchase_price = 0;
                $average_purchase_price = Helper::getAveragePrice($productId, $price, $qty, $shop);
                $product->average_price = $average_purchase_price;
                $product->save();
            }

            $supplierDue = new SupplierDue();
            $supplierDue->supplier_id =  $supplier;
            $supplierDue->purchase_id =  $sale->id;
            $supplierDue->payment_method =  $request->payment_method;
            $supplierDue->payment_type = Helper::getPaymentMethodName($request->payment_method)->payment_name;
            $supplierDue->paid = $request->paid ?: 0;
            $supplierDue->due = ($request->total_amount) - ($sale->paid);
            $supplierDue->note = 'Sale Invoice';
            if ($this->User->user_type == "Admin") {
                $supplierDue->admin_id = $this->User->id;
            } else {
                $supplierDue->admin_id = $this->User->admin_id;
                $supplierDue->employee_id = $this->User->id;
            }
            $supplierDue->created_user_id = $this->User->id;
            $supplierDue->updated_user_id = $this->User->id;
            $supplierDue->save();
        }
         if ((Auth::user()->user_type === 'Employee')) {           
                $data = [
                    'message' => 'Your Staff ' . Auth::user()->name . '  Create A Invoice ' .$sale->invoice_no,

                ];
                User::find(Auth::user()->admin_id)->notify(new Usernotification($data));
            }
       
        DB::commit();
        if ($request->has('sale')) {
            Toastr::success("Sale Created Successfully  Done. Add  Another Sale", "Success");
            return redirect()->back();
        } else {
            Toastr::success("Sale Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.sales.index');
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
     * @param  \App\Models\Category   $sale
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.sales.index'), 'name' => "Sale"],
            ['name' => 'Show'],
        ];
        // try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Sale::with('shop', 'user','purchasedetails.product')->findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Admin') {
                $data = Sale::with('shop', 'user','purchasedetails.product')->whereadmin_id($this->User->id)->findOrFail(decrypt($id));
                
            } else {
                $data = Sale::with('shop', 'user','purchasedetails.product')->whereadmin_id($this->User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.Purchases.show', compact('breadcrumbs'))->with('sale', $data);
        // } catch (\Exception $e) {
        //     $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
        //     Toastr::error($response['message'], "Error");
        //     return back();
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category   $sale
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Sale::with('shop', 'user','purchasedetails.product')->findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Admin') {
                $data = Sale::with('shop', 'user','purchasedetails.product')->whereadmin_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = Sale::with('shop', 'user','purchasedetails.product')->whereadmin_id($this->User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.Purchases.edit')->with('sale', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    {
       
        $this->validate(
            $request,
            [
                
                
                'payment_method' => 'required|min:1|max:300',
                'paid' => 'max:99999999',
                'total_amount' => 'required|numeric|between:1,99999999',
                'product_id.*' => 'required',
                'product_quantity.*' => 'required',
                'product_price.*' => 'required|numeric|between:1,99999999',
                'product_total_price' => 'required',

            ],

            [
             
                
                'payment_method.required' => "The Payment Method name field is required",
                'paid.max' => "The Sale name Maximum Length 190",
                'total_amount.required' => "The Total amount name field is required",
                'total_amount.min' => "The Total amount Minimum Length 1",
                'total_amount.max' => "The Total amount Maximum Length 99999999",
                'product_id.required' => "The  Product name field is required",
                'product_price.required' => "The  Product Price name field is required",
                'product_price.min' => "The Product Price Minimum Length 1",
                'product_price.max' => "The Product Price  Maximum Length 99999999",
                'product_quantity.required' => "The Product  Quantity name field is required",
                'product_total_price.required' => "The Product  Quantity name field is required",
            ]
        );

       try {
        DB::beginTransaction();
        $sale =Sale::find($id);
        $shop =    $sale->shop_id;
        $supplier =  $sale->supplier_id;
        $date = date('Y-m-d');
        $sale->shop_id = $shop;
        $sale->supplier_id = $supplier;
        $sale->date = $date;
        $sale->total_vat = $request->total_vat;
        $sale->reference = $request->reference;
        $sale->total_discount = $request->total_discount ?: 0;
        $sale->sub_total = ($request->total_amount) - (($sale->total_vat) + ($request->total_discount));
        $sale->payment_type_id = $request->payment_method;
        $sale->paid = $request->paid ?: 0;
        $sale->due = ($request->total_amount) - ($sale->paid);
        $sale->grand_total = $request->total_amount;
        $sale->description = $request->description;
        $sale->created_user_id = $this->User->id;
        $sale->updated_user_id = $this->User->id;
        $sale->save();
        if ($sale) {
            $purchaseProducts = $request->product_id;
            for ($i = 0; $i < count($purchaseProducts); $i++) {
                $productId = $request->product_id[$i];
                $price = $request->product_price[$i];
                $name = $request->product_name[$i];
                $qty = $request->product_quantity[$i];
                $total = $request->product_total_price[$i];
                $productVat = $request->product_vat[$i];
                $productVatAmount = $request->product_vat_amount[$i];
                $product = Product::find($productId);
                $checkShop = ShopCurrentStock::whereproduct_id($productId)->whereshop_id($shop)->first();
                if ($checkShop) {
                    if ($checkShop->stock_qty<$qty) {
                        $newQty=$checkShop->stock_qty-$qty;
                        $checkShop->decrement('stock_qty',$newQty);
                        
                    }
                    if ($checkShop->stock_qty>$qty) {
                     $newQty=$qty-$checkShop->stock_qty;
                      $checkShop->increment('stock_qty',$newQty);
                    }
                  
                } else {
                    $checkShop = new ShopCurrentStock();
                    if (Auth::user()->user_type == 'Admin') {
                        $checkShop->admin_id = Auth::id();
                    } else {
                        $checkShop->admin_id = Auth::user()->admin_id;
                    }
                    $checkShop->shop_id = $shop;
                    $checkShop->product_id = $productId;
                    $checkShop->stock_qty = $qty;
                    $checkShop->save();
                }
                if($checkShop){
                    $purchaseId=$sale->id;
                    $purchaseCheck=PurchaseDetails::wherepurchase_id($purchaseId)->whereproduct_id($productId)->first();
                    if($purchaseCheck){
                        $purchaseCheck->qty =  $qty;
                         $purchaseCheck->purchase_price = $price;
                        $purchaseCheck->vat_percent = $productVat;
                        $purchaseCheck->vat_amount = $productVatAmount;
                        $purchaseCheck->total_price = $total;
                        $purchaseCheck->save();
                    }
                    else{
                    $purchaseDetail = new PurchaseDetails();
                    $purchaseDetail->purchase_id =$purchaseId;
                    $purchaseDetail->product_id = $productId;
                    $purchaseDetail->product_name = $name;
                    $purchaseDetail->qty =  $qty;
                    $purchaseDetail->average_purchase_price = $product->average_price;
                    $purchaseDetail->purchase_price = $price;
                    $purchaseDetail->vat_percent = $productVat;
                    $purchaseDetail->vat_amount = $productVatAmount;
                    $purchaseDetail->total_price = $total;
                    $purchaseDetail->save();
                     }
                }
               
               
                $average_purchase_price = 0;
                $average_purchase_price = Helper::getAveragePrice($productId, $price, $qty, $shop);
                $product->average_price = $average_purchase_price;
                $product->save();
            }

            $supplierDue = SupplierDue::wheresupplier_id($supplier)->wherepurchase_id($purchaseId)->first();
            $supplierDue->payment_method =  $request->payment_method;
            $supplierDue->payment_type = Helper::getPaymentMethodName($request->payment_method)->payment_name;
            $supplierDue->paid = $request->paid ?: 0;
            $supplierDue->due = ($request->total_amount) - ($sale->paid);
            $supplierDue->note = 'Sale Invoice';
            if ($this->User->user_type == "Admin") {
                $supplierDue->admin_id = $this->User->id;
            } else {
                $supplierDue->admin_id = $this->User->admin_id;
                $supplierDue->employee_id = $this->User->id;
            }
            $supplierDue->created_user_id = $this->User->id;
            $supplierDue->updated_user_id = $this->User->id;
            $supplierDue->save();
        }
         if ((Auth::user()->user_type === 'Employee')) {           
                $data = [
                    'message' => 'Your Staff ' . Auth::user()->name . '  Update A Invoice ' .$sale->invoice_no,

                ];
                User::find(Auth::user()->admin_id)->notify(new Usernotification($data));
            }
       
        DB::commit();
        if ($request->has('sale')) {
            Toastr::success("Sale Update Successfully  Done. Add  Another Sale", "Success");
            return redirect()->back();
        } else {
            Toastr::success("Sale Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.sales.index');
        }
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
     * @param  \App\Models\Category   $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale  $sale)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $sale = Sale::findOrFail($request->id);
        $sale->status = $request->status;
        if ($sale->save()) {
            return 1;
        }
        return 0;
    }
    public function findShopCurrentStock(Request $request)
    {
        if ($request->has('term')) {
            if (Auth::user()->user_type == 'Superadmin') {
                $data = ShopCurrentStock::with('product')->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->inRandomOrder()->take(20)->get();
            } elseif (Auth::user()->user_type == 'Admin') {
                $data = ShopCurrentStock::whereadmin_id(Auth::id())->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->inRandomOrder()->take(20)->get();
            } else {

                $data = ShopCurrentStock::whereadmin_id(Auth::user()->admin_id)->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->inRandomOrder()->take(20)->get();
            }
            $results = array();
            foreach ($data as  $v) {
                $results[] = ['id' => $v->id, 'value' => $v->product_name.' ('.$v->barcode.')', 'price' => $v->purchase_price, 'saleprice' => $v->sale_price, 'tax' => $v->vat];
            }

            return response()->json($results);
        }
    
    }
    public function findCustomer(Request $request)
    {
        if ($request->has('q')) {
           
            if (Auth::user()->user_type == 'Superadmin') {
                $data = Customer::wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('customer_name', 'like', '%' . $request->quotemeta . '%')->orWhere('customer_phone', 'like', '%' . $request->q . '%');
                })->select('id', 'customer_name', 'customer_phone', 'discount')->take(20)->get();
            } elseif (Auth::user()->user_type == 'Admin') {
               
                $data = Customer::whereadmin_id(Auth::id())->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('customer_name', 'like', '%' . $request->q . '%')->orWhere('customer_phone', 'like', '%' . $request->q . '%');
                })->select('id', 'customer_name', 'customer_phone', 'discount')->get();
            } else {

                $data = Customer::whereadmin_id(Auth::user()->admin_id)->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('customer_name', 'like', '%' . $request->q . '%')->orWhere('customer_phone', 'like', '%' . $request->q . '%');
                })->select('id', 'customer_name', 'customer_phone', 'discount')->get();
            }
          
            return response()->json($data);
        
    }
    }
}
