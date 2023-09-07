<?php

namespace App\Http\Controllers\Common;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Validation\Rule;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
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

        $this->middleware('permission:product-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        try {
            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Product::with('brand', 'category','shopcurrentstock')->select('product_name', 'brand_id', 'category_id', 'size', 'discount', 'vat', 'sale_price', 'admin_id', 'id', 'status', 'path', 'photo', 'barcode')->latest();
            } elseif ($User->user_type == 'Admin') {
                $data = Product::with('brand', 'category','shopcurrentstock')->whereadmin_id($this->User->id)->select('product_name', 'brand_id', 'category_id', 'size', 'discount', 'vat', 'sale_price', 'admin_id', 'id', 'status', 'path', 'photo', 'barcode')->latest();
            } else {
                $data = Product::with('brand', 'category','shopcurrentstock')->whereadmin_id($this->User->id)->whereemployee_id($User->id)->select('product_name', 'brand_id', 'category_id', 'size', 'discount', 'vat', 'sale_price', 'admin_id', 'id', 'status', 'path', 'photo', 'barcode')->latest();
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.products.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                        $btn .= '<a href=' . route(request()->segment(1) . '.products.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } else {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" checked="" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        }
                    })
                    ->addColumn('image', function ($data) {
                        return '<a title="Click for View" data-lightbox="roadtrip" href="' . url($data->path . '/' . $data->photo) . '"><img id="demo-test-gallery" class="border-radius-lg shadow demo-gallery" src="' . url($data->path . '/' . $data->photo) . '" height="40px" width="40px"  />';
                    })
                    ->addColumn('stock', function ($data) {
                       $stock= $data->shopcurrentstock->sum('stock_qty');
                       if($data->low_quantity<$stock){
                        return '<span class="badge badge-success badge-sm">' .  $stock . '</span>';
                       }else{
                        return '<span class="badge badge-danger badge-sm">' .  $stock . '</span>';
                       }
                      
                  
                    
                })
                    ->rawColumns(['image', 'action', 'status','stock'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.products.index'), 'name' => "Product"],
                ['name' => 'List'],
            ];
            return view('backend.common.products.index', compact('breadcrumbs'));
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }
    public function brandProducts(Request $request, $brandId)
    {

        try {

            $User = $this->User;

            if ($User->user_type == 'Superadmin') {
                $data = Product::with('brand', 'category')->where('brand_id', decrypt($brandId))->latest();
            } elseif ($User->user_type == 'Admin') {

                $data =  Product::with('brand', 'category')->whereadmin_id(Auth::id())->where('brand_id', decrypt($brandId))->latest();
            } else {

                $data =  Product::with('brand', 'category')->whereemployee_id($User->id)->where('brand_id', decrypt($brandId))->latest();
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.products.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                        $btn .= '<a href=' . route(request()->segment(1) . '.products.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } else {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" checked="" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        }
                    })
                    ->addColumn('image', function ($data) {
                        return '<a title="Click for View" data-lightbox="roadtrip" href="' . url($data->path . '/' . $data->photo) . '"><img id="demo-test-gallery" class="border-radius-lg shadow demo-gallery" src="' . url($data->path . '/' . $data->photo) . '" height="40px" width="40px"  />';
                    })

                    ->rawColumns(['image', 'action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.products.index'), 'name' => "Product"],
                ['name' => 'Brand Product List'],
            ];
            return view('backend.common.products.brand_products', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.products.index'), 'name' => "Product"],
            ['name' => 'Create'],
        ];
        return view('backend.common.products.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->user_type == "Admin") {


            $this->validate(
                $request,
                [
                    'category_id' => 'required|min:1|max:198',
                    'sub_category_id' => 'required|min:1|max:198',
                    'color' => 'required|min:1|max:300',
                    'brand_id' => 'required|min:1|max:190',
                    'purchase_price' => 'required|min:1|max:99999999',
                    'sale_price' => 'required|min:1|max:99999999',
                    'vat' => 'required',
                    'discount' => 'required',
                    'product_name' => [
                        'required', 'min:1',
                        'max:198', Rule::unique('products')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->id);
                        })
                    ],
                    'barcode' => [
                        'required', 'min:1',
                        'max:20', 'alpha_dash', Rule::unique('products')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->id);
                        })
                    ]
                ],

                [
                    'product_name.unique' => "The Product name must be unique",
                    'product_name.required' => "The Product name field is required",
                    'product_name.min' => "The Product name Minimum Length 1",
                    'product_name.max' => "The Product name Maximum Length 190",
                    'category_id.required' => "The Category name field is required",
                    'category_id.min' => "The Category Minimum Length 1",
                    'category_id.max' => "The Category Maximum Length 99999999",
                    'sub_category_id.required' => "The  Sub Category name field is required",
                    'sub_category_id.min' => "The  Sub Category Minimum Length 1",
                    'sub_category_id.max' => "The  Sub Category Maximum Length 100000",
                    'purchase_price.required' => "The  Purchase Price name field is required",
                    'purchase_price.min' => "The Purchase Price Minimum Length 1",
                    'purchase_price.max' => "The Purchase Price  Maximum Length 99999999",
                    'sale_price.required' => "The Sale  Price name field is required",
                    'sale_price.min' => "The Sale Price  Minimum Length 1",
                    'sale_price.max' => "The Sale Price Maximum Length 100000",


                ]
            );
        } else {


            $this->validate(
                $request,
                [
                    'category_id' => 'required|min:1|max:198',
                    'sub_category_id' => 'required|min:1|max:198',
                    'color' => 'required|min:1|max:300',
                    'brand_id' => 'required|min:1|max:190',
                    'purchase_price' => 'required|numeric|min:1|max:99999999',
                    'sale_price' => 'required|numeric|min:1|max:99999999',
                    'description' => 'max:99999999',
                    'size' => 'required',
                    'discount' => 'required',
                    'product_name' => [
                        'required', 'min:1',
                        'max:198', Rule::unique('products')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->admin_id);
                        })
                    ],
                    'barcode' => [
                        'required', 'min:1',
                        'max:20', 'alpha_dash', Rule::unique('products')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->admin_id);
                        })
                    ]
                ],

                [
                    'product_name.unique' => "The Product name must be unique",
                    'product_name.required' => "The Product name field is required",
                    'product_name.min' => "The Product name Minimum Length 1",
                    'product_name.max' => "The Product name Maximum Length 190",
                    'category_id.required' => "The Category name field is required",
                    'category_id.min' => "The Category Minimum Length 1",
                    'category_id.max' => "The Category Maximum Length 99999999",
                    'sub_category_id.required' => "The  Sub Category name field is required",
                    'sub_category_id.min' => "The  Sub Category Minimum Length 1",
                    'sub_category_id.max' => "The  Sub Category Maximum Length 100000",
                    'purchase_price.required' => "The  Purchase Price name field is required",
                    'purchase_price.min' => "The Purchase Price Minimum Length 1",
                    'purchase_price.max' => "The Purchase Price  Maximum Length 99999999",
                    'sale_price.required' => "The Sale  Price name field is required",
                    'sale_price.min' => "The Sale Price  Minimum Length 1",
                    'sale_price.max' => "The Sale Price Maximum Length 100000",


                ]
            );
        }
        try {
            DB::beginTransaction();
            $product = new Product();
            $product->product_name = $request->product_name;
            $product->brand_id = $request->brand_id;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->barcode = $request->barcode;
            $product->slug = Generate::Slug($request->product_name);
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->rack_number = $request->rack_number;
            $product->made_in = $request->made_in;
            $product->purchase_price = $request->purchase_price;
            $product->average_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->vat = $request->vat;
            $product->discount = $request->discount;
            $product->low_quantity = $request->low_quantity ?: 0;
            $product->other_color = json_encode($request->other_color);
            $product->created_user_id = $this->User->id;
            $product->updated_user_id = $this->User->id;
            if ($request->hasfile('photo')) {
                $this->validate($request, [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:800',

                ]);
                if (Auth::user()->user_type == 'Admin') {

                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/", 0777, true);
                    }

                    $ex = $request->photo->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->product_name, 40)));
                    $name = $rand . "." . $ex;
                    $request->photo->move(storage_path('/app/public/files/shares/uploads/' . Auth::id()), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);
                    $product->path = 'storage/files/shares/uploads/' . Auth::id();
                    $product->photo = $name;
                } else {

                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::user()->admin_id . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::user()->admin_id . "/thumbs/", 0777, true);
                    }

                    $ex = $request->photo->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->product_name, 40)));
                    $name = $rand . "." . $ex;
                    $request->photo->move(storage_path('/app/public/files/shares/uploads/' . Auth::user()->admin_id), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::user()->admin_id . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::user()->admin_id . '/thumbs/' . $name, 60);
                    $product->path = 'app/public/files/shares/uploads/' . Auth::user()->admin_id;
                    $product->photo = $name;
                }
            } else {
                $product->path =  '/storage/files/shares/backend';
                $product->photo = 'not-found.webp';
            }


            $product->sku = trim(Str::limit(Str::upper($request->product_name), 3, '')) . Size::find($request->size)->size . trim(Str::limit(Str::upper(Color::find($request->color)->color_name), 2, ''));
            if (Auth::user()->user_type == 'Admin') {
                $product->admin_id = Auth::id();
            } else {
                $product->admin_id = Auth::user()->admin_id;
                $product->employee_id = Auth::id();
            }
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();
            DB::commit();
            if ($request->has('saveandback')) {
                Toastr::success("Product Created Successfully  Done. Add  Another Product", "Success");
                return redirect()->back();
            } else {
                Toastr::success("Product Created Successfully", "Success");
                return redirect()->route(request()->segment(1) . '.products.index');
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
     * @param  \App\Models\Category   $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.products.index'), 'name' => "Product"],
            ['name' => 'Show'],
        ];
        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Product::with('brand', 'category')->findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Admin') {
                $data = Product::with('brand', 'category')->whereadmin_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = Product::with('brand', 'category')->whereadmin_id($this->User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.products.show', compact('breadcrumbs'))->with('product', $data);
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category   $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            $User = $this->User;
            if ($User->user_type == 'Superadmin') {
                $data = Product::with('brand', 'category')->findOrFail(decrypt($id));
            } elseif ($User->user_type == 'Admin') {
                $data = Product::with('brand', 'category')->whereadmin_id($this->User->id)->findOrFail(decrypt($id));
            } else {
                $data = Product::with('brand', 'category')->whereadmin_id($this->User->admin_id)->findOrFail(decrypt($id));
            }
            return view('backend.common.products.edit')->with('product', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->user_type == "Admin") {


            $this->validate(
                $request,
                [
                    'category_id' => 'required|min:1|max:198',
                    'sub_category_id' => 'required|min:1|max:198',
                    'color' => 'required|min:1|max:300',
                    'brand_id' => 'required|min:1|max:190',
                    'purchase_price' => 'required|min:1|max:99999999',
                    'sale_price' => 'required|min:1|max:99999999',
                    'vat' => 'required',
                    'discount' => 'required',
                    'product_name' => [
                        'required', 'min:1',
                        'max:198', Rule::unique('products')->ignore($id, 'id')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->id);
                        })
                    ],
                    'barcode' => [
                        'required', 'min:1',
                        'max:20', 'alpha_dash', Rule::unique('products')->ignore($id, 'id')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->id);
                        })
                    ]
                ],

                [
                    'product_name.unique' => "The Product name must be unique",
                    'product_name.required' => "The Product name field is required",
                    'product_name.min' => "The Product name Minimum Length 1",
                    'product_name.max' => "The Product name Maximum Length 190",
                    'category_id.required' => "The Category name field is required",
                    'category_id.min' => "The Category Minimum Length 1",
                    'category_id.max' => "The Category Maximum Length 99999999",
                    'sub_category_id.required' => "The  Sub Category name field is required",
                    'sub_category_id.min' => "The  Sub Category Minimum Length 1",
                    'sub_category_id.max' => "The  Sub Category Maximum Length 100000",
                    'purchase_price.required' => "The  Purchase Price name field is required",
                    'purchase_price.min' => "The Purchase Price Minimum Length 1",
                    'purchase_price.max' => "The Purchase Price  Maximum Length 99999999",
                    'sale_price.required' => "The Sale  Price name field is required",
                    'sale_price.min' => "The Sale Price  Minimum Length 1",
                    'sale_price.max' => "The Sale Price Maximum Length 100000",


                ]
            );
        } else {


            $this->validate(
                $request,
                [
                    'category_id' => 'required|min:1|max:198',
                    'sub_category_id' => 'required|min:1|max:198',
                    'color' => 'required|min:1|max:300',
                    'brand_id' => 'required|min:1|max:190',
                    'purchase_price' => 'required|numeric|min:1|max:99999999',
                    'sale_price' => 'required|numeric|min:1|max:99999999',
                    'description' => 'max:99999999',
                    'size' => 'required',
                    'discount' => 'required',
                    'product_name' => [
                        'required', 'min:1',
                        'max:198', Rule::unique('products')->ignore($id, 'id')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->admin_id);
                        })
                    ],
                    'barcode' => [
                        'required', 'min:1',
                        'max:20', 'alpha_dash', Rule::unique('products')->ignore($id, 'id')->where(function ($query) {
                            return $query->where('admin_id', Auth::user()->admin_id);
                        })
                    ]
                ],

                [
                    'product_name.unique' => "The Product name must be unique",
                    'product_name.required' => "The Product name field is required",
                    'product_name.min' => "The Product name Minimum Length 1",
                    'product_name.max' => "The Product name Maximum Length 190",
                    'category_id.required' => "The Category name field is required",
                    'category_id.min' => "The Category Minimum Length 1",
                    'category_id.max' => "The Category Maximum Length 99999999",
                    'sub_category_id.required' => "The  Sub Category name field is required",
                    'sub_category_id.min' => "The  Sub Category Minimum Length 1",
                    'sub_category_id.max' => "The  Sub Category Maximum Length 100000",
                    'purchase_price.required' => "The  Purchase Price name field is required",
                    'purchase_price.min' => "The Purchase Price Minimum Length 1",
                    'purchase_price.max' => "The Purchase Price  Maximum Length 99999999",
                    'sale_price.required' => "The Sale  Price name field is required",
                    'sale_price.min' => "The Sale Price  Minimum Length 1",
                    'sale_price.max' => "The Sale Price Maximum Length 100000",


                ]
            );
        }
        try {
            DB::beginTransaction();
            $product = Product::find($id);
            $product->product_name = $request->product_name;
            $product->brand_id = $request->brand_id;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->barcode = $request->barcode;
            $product->slug = Generate::Slug($request->product_name);
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->rack_number = $request->rack_number;
            $product->made_in = $request->made_in;
            $product->purchase_price = $request->purchase_price;
            $product->average_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->vat = $request->vat;
            $product->discount = $request->discount;
            $product->low_quantity = $request->low_quantity ?: 0;
            $product->other_color = json_encode($request->other_color);
            $product->updated_user_id = $this->User->id;
            if ($request->hasfile('photo')) {

                $this->validate($request, [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:800',

                ]);
                if (Auth::user()->user_type == 'Admin') {

                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/", 0777, true);
                    }

                    if ($product->photo !== 'not-found.webp') {
                        $imagepath = (storage_path() . '/app/files/shares/uploads/' . Auth::id()) . $product->photo;
                        $thumbimagepath = (storage_path() . '/app/files/shares/uploads/' . Auth::id() . '/thumbs/') . $product->photo;
                        if (file_exists($imagepath) && ($thumbimagepath)) {
                            unlink($imagepath) && unlink($thumbimagepath);
                        }
                    }


                    $ex = $request->photo->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->product_name, 40)));
                    $name = $rand . "." . $ex;
                    $request->photo->move(storage_path('/app/public/files/shares/uploads/' . Auth::id()), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);
                    $product->path = 'storage/files/shares/uploads/' . Auth::id();
                    $product->photo = $name;
                } else {

                    if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::user()->admin_id . "/thumbs/")) {
                        mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::user()->admin_id . "/thumbs/", 0777, true);
                    }
                    if ($product->photo !== 'not-found.webp') {
                        $imagepath = (storage_path() . '/app/files/shares/uploads/' . Auth::user()->admin_id) . $product->photo;
                        $thumbimagepath = (storage_path() . '/app/files/shares/uploads/' . Auth::user()->admin_id . '/thumbs/') . $product->photo;
                        if (file_exists($imagepath) && ($thumbimagepath)) {
                            unlink($imagepath) && unlink($thumbimagepath);
                        }
                    }
                    $ex = $request->photo->extension();
                    $rand = uniqid(Generate::Slug(Str::limit($request->product_name, 40)));
                    $name = $rand . "." . $ex;
                    $request->photo->move(storage_path('/app/public/files/shares/uploads/' . Auth::user()->admin_id), $name, 60);

                    $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::user()->admin_id . '/' . $name)->resize(35, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::user()->admin_id . '/thumbs/' . $name, 60);
                    $product->path = 'app/public/files/shares/uploads/' . Auth::user()->admin_id;
                    $product->photo = $name;
                }
            } else {
                $product->path =  '/storage/files/shares/backend';
                $product->photo = 'not-found.jpg';
            }


            $product->sku = trim(Str::limit(Str::upper($request->product_name), 3, '')) . Size::find($request->size)->size . trim(Str::limit(Str::upper(Color::find($request->color)->color_name), 2, ''));
            if (Auth::user()->user_type == 'Admin') {
                $product->admin_id = Auth::id();
            } else {
                $product->admin_id = Auth::user()->admin_id;
                $product->employee_id = Auth::id();
            }
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();
            DB::commit();
            if ($request->has('saveandback')) {
                Toastr::success("Product Created Successfully  Done. Add  Another Product", "Success");
                return redirect()->back();
            } else {
                Toastr::success("Product Created Successfully", "Success");
                return redirect()->route(request()->segment(1) . '.products.index');
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
     * @param  \App\Models\Category   $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product  $product)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }
    public function checkProductName($id)
    {
        if (Auth::user()->user_type == "Admin") {
            $product = Product::whereadmin_id(Auth::id())->whereproduct_name($id)->first();
        } else {
            $product = Product::whereadmin_id(Auth::user()->admin_id)->whereproduct_name($id)->first();
        }
        if ($product) {
            return response()->json(['success' => false, 'message' => 'duplicate']);
        } else {
            return response()->json(['success' => true, 'message' => 'no duplicate']);
        }
    }
    public function checkBarcode($id)
    {
        if (Auth::user()->user_type == "Admin") {
            $product = Product::whereadmin_id(Auth::id())->wherebarcode($id)->first();
        } else {
            $product = Product::whereadmin_id(Auth::user()->admin_id)->wherebarcode($id)->first();
        }
        if ($product) {
            return response()->json(['success' => false, 'message' => 'duplicate']);
        } else {
            return response()->json(['success' => true, 'message' => 'no duplicate']);
        }
    }

    //for ui item barcode data

    public function findProduct(Request $request)
    {

        if ($request->has('term')) {
            if (Auth::user()->user_type == 'Superadmin') {
                $data = Product::wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->select('id', 'product_name', 'purchase_price', 'sale_price', 'barcode', 'vat')->inRandomOrder()->take(20)->get();
            } elseif (Auth::user()->user_type == 'Admin') {
                $data = Product::whereadmin_id(Auth::id())->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->select('id', 'product_name', 'purchase_price', 'sale_price', 'barcode', 'vat')->inRandomOrder()->take(20)->get();
            } else {

                $data = Product::whereadmin_id(Auth::user()->admin_id)->wherestatus(1)->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->term . '%')->orWhere('barcode', 'like', '%' . $request->term . '%');
                })->select('id', 'product_name', 'purchase_price', 'sale_price', 'barcode', 'vat')->inRandomOrder()->take(20)->get();
            }
            $results = array();
            foreach ($data as  $v) {
                $results[] = ['id' => $v->id, 'value' => $v->product_name.' ('.$v->barcode.')', 'price' => $v->purchase_price, 'saleprice' => $v->sale_price, 'tax' => $v->vat];
            }

            return response()->json($results);
        }
    }
}
