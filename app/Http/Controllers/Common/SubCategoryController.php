<?php

namespace App\Http\Controllers\Common;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;


class SubCategoryController extends Controller
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

        $this->middleware('permission:category-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        try {
            $User = $this->User;
            $data = Category::latest();

            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '';
                        if ($User->can('category-edit')) {
                            $btn = '<a href=' . route(request()->segment(1) . '.categories.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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

                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.brands.index'), 'name' => "Brand"],
                ['name' => 'List'],
            ];
            return view('backend.superadmin.categories.index', compact('breadcrumbs'));
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
        return view('backend.superadmin.categories.create', compact('breadcrumbs'));
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
                'category_name' => 'required|min:1|max:198|unique:categories',
            ],
            [
                'category_name.unique' => "The Category name field is unique",
                'category_name.required' => "The Category name field is required",
                'category_name.min' => "The Category Minimum Length 1",
                'category_name.max' => "The Category Maximum Length 190",

            ]
        );

        try {
            DB::beginTransaction();
            $category = new Category();
            $category->superadmin_id = $this->User->id;
            $category->category_name = $request->category_name;
            $category->slug = Generate::Slug($request->category_name);
            $category->save();
            DB::commit();

            Toastr::success("Category Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.categories.index');
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
                $data = Category::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Category::findOrFail($id);
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
           
            $data = Category::findOrFail(decrypt($id));

            return view('backend.superadmin.categories.edit')->with('category', $data);
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
     * @param  \App\Models\Category   $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'category_name' => 'required|min:1|max:198|unique:categories,category_name,' . $id,


            ],
            [
                'category_name.unique' => "The Category name field is unique",
                'category_name.required' => "The Category name field is required",
                'category_name.min' => "The Category Minimum Length 1",
                'category_name.max' => "The Category Maximum Length 190"
            ]
        );

        try {
            DB::beginTransaction();
            $category = Category::find($id);
            $category->superadmin_id = $this->User->id;
            $category->category_name = $request->category_name;
            $category->slug = Generate::Slug($request->category_name);
            $category->save();
            DB::commit();

            Toastr::success("Category Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.categories.index');
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
    public function destroy(Category  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }

    
   
}
