<?php

namespace App\Http\Controllers\Common;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BrandController extends Controller
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

        $this->middleware('permission:brand-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:brand-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:brand-edit', ['only' => ['edit', 'update','updateStatus']]);
        $this->middleware('permission:brand-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
       
        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Brand::with('user')->latest();
            } elseif($User->user_type == 'Admin') {
                $data = Brand::with('user')->whereadmin_id($this->User->id)->latest();
            } else {
                $data = Brand::with('user')->whereadmin_id($this->User->admin_id)->latest();
               
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('brand-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.brands.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
                    ->addColumn('products', function ($data) {
                        $products = Product::where('brand_id',$data->id)->count();
                            return '<div class="form-check form-switch">'.'<a href=' . url(request()->segment(1) . '/brand-products', (encrypt($data->id))) . ' class="btn btn-primary btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"> ('.$products.')'.'</i></a> </div>';
                        
                    })
                   ->rawColumns(['products','action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.brands.index'), 'name' => "Brand"],
                ['name' => 'List'],
            ];

            return view('backend.common.brands.index',compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.brands.index'), 'name' => "Brand"],
            ['name' => 'Create'],
        ];
        return view('backend.common.brands.create',compact('breadcrumbs'));
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
               'brand_name' => ['required','min:1',
                'max:198', Rule::unique('brands')->where(function ($query){
                        return $query->where('admin_id', Auth::user()->id);
                    })
                ]               
                ],
            [
                'brand_name.unique' => "The Brand name field need to be unique",
                'brand_name.required' => "The Brand name field is required",
                'brand_name.min' => "The Brand Minimum field length 1",
                'brand_name.max' => "The Brand Maximum field length 100",
                
                
                

            ]);
        }
        else{
            $this->validate($request,
            [
              'status' => 'required|min:0|max:100',
                'brand_name' => ['required','min:1',
                'max:198', Rule::unique('brands')->where(function ($query) {
                        return $query->where('admin_id', Auth::user()->admin_id);
                    })
                ]               
                ],
            [
                'brand_name.unique' => "The Brand name field need to be unique",
                'brand_name.required' => "The Brand name field is required",
                'brand_name.min' => "The Brand Minimum field length 1",
                'brand_name.max' => "The Brand Maximum field length 100",
                
                
                

            ]
        );
        }

        try {
            DB::beginTransaction();
            $brand = new Brand();
            $brand->brand_name = $request->brand_name;
           $brand->slug = Generate::Slug($request->brand_name);
           if($this->User->user_type=="Admin"){
            $brand->admin_id = $this->User->id;
           }else{
            $brand->admin_id = $this->User->admin_id;
            $brand->employee_id = $this->User->id;
           }
           $brand->created_user_id = $this->User->id;
           $brand->updated_user_id = $this->User->id;
           $brand->status = $request->status;
           $brand->save();
            DB::commit();
            Toastr::success("Brand Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.brands.index');
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
                $data = Brand::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Brand::findOrFail($id);
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
                $data = Brand::findOrFail(decrypt($id));
            }
            elseif ($User->user_type == 'Admin') {
                $data = Brand::whereadmin_id($User->id)->findOrFail(decrypt($id));
            }
             else {
                $data = Brand::whereadmin_id($User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.brands.edit')->with('brand', $data);
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
              
               'status' => 'required|min:0|max:100',
                'brand_name' => ['required','min:1',
                'max:198', Rule::unique('brands')->ignore($id, 'id')->where(function ($query){
                        return $query->where('admin_id', Auth::user()->id);
                    })
                ]               
                ],
            [
                'brand_name.unique' => "The Brand name field need to be unique",
                'brand_name.required' => "The Brand name field is required",
                'brand_name.min' => "The Brand Minimum field length 1",
                'brand_name.max' => "The Brand Maximum field length 100",
                
                
                

            ]);
        }
        else{
            $this->validate($request,
            [
              
                'brand_name' => ['required','min:1',
                'max:198', Rule::unique('brands')->ignore($id, 'id')->where(function ($query) {
                        return $query->where('admin_id', Auth::user()->admin_id);
                    })
                ]               
                ],
            [
                'brand_name.unique' => "The Brand name field need to be unique",
                'brand_name.required' => "The Brand name field is required",
                'brand_name.min' => "The Brand Minimum field length 1",
                'brand_name.max' => "The Brand Maximum field length 100",

            ]
        );
        }

        try {
            DB::beginTransaction();
            $brand = Brand::find($id);
            $brand->brand_name = $request->brand_name;
            $brand->slug = Generate::Slug($request->brand_name);
           if($this->User->user_type=="Admin"){
            $brand->admin_id = $this->User->id;
           }else{
            $brand->admin_id = $this->User->admin_id;
            $brand->employee_id = $this->User->id;
           }
           $brand->updated_user_id = $this->User->id;
           $brand->status = $request->status;
           $brand->save();
            DB::commit();
           Toastr::success("Brand Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.brands.index');
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
    public function destroy(Brand  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $category = Brand::findOrFail($request->id);
        $category->status = $request->status;
        $category->updated_user_id = Auth::id();
        if ($category->save()) {
            return 1;
        }
        return 0;
    }
}
