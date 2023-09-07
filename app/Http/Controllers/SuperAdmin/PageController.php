<?php

namespace App\Http\Controllers\Superadmin;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{  
     private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
           
            return $next($request);
        });

       
    }
    public function index(Request $request)
    {

        try {
            
         $data = Page::latest();
            
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a href=' . route(request()->segment(1) . '.pages.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
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
                        return '<a title="Click for View" data-lightbox="roadtrip" href="' . asset($data->image) . '"><img id="demo-test-gallery" class="border-radius-lg shadow demo-gallery" src="' . asset($data->image) . '" height="40px" width="40px"  />';
                    })
                    ->addColumn('link', function ($data) {
                        return '<a title="Click for View" target="_blank" href="' . url($data->slug) . '"><i class="fa fa-link"></i></a>';
                    })
                    ->rawColumns(['image', 'action', 'status','link'])
                    ->make(true);
            }

            return view('backend.superadmin.pages.index');
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
        
        return view('backend.superadmin.pages.create');
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
                'page_name' => 'required|min:1|max:198|unique:pages',
                'slug' => 'required|min:1|max:198|unique:pages',
                'header_description' => 'required|min:3|max:30000',
                'footer_description' => 'required|min:3|max:30000',
                 'image' => 'required',
                
                
            ],
            [
                'short_description.required' => "The Short Description name field is required",
                'short_description.min' => "The Short Description Minimum Length 1",
                'short_description.max' => "The Short Description Maximum Length 190",
                'header_description.required' => "The Header Description name field is required",
                'header_description.min' => "The Header Description Minimum Length 1",
                'header_description.max' => "The Header Description Maximum Length 100000",
                'footer_description.required' => "The Footer Description name field is required",
                'footer_description.min' => "The Footer Description Minimum Length 1",
                'footer_description.max' => "The Footer Description Maximum Length 100000",
                

            ]
        );

        try {
            DB::beginTransaction();
            $page = new Page();
            $page->superadmin_id = $this->User->id;
            $page->page_name = $request->page_name;
            $page->slug = Generate::Slug($request->slug);
            $page->meta_title = $request->meta_title ?: $request->name;
            $page->json_screma = $request->json_screma;
            $page->keyword = $request->keyword;
            $page->header_description = $request->header_description;
            $page->footer_description = $request->footer_description;
            $page->image = $request->image;
            $page->save();
            DB::commit();

            Toastr::success("Page Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.pages.index');
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
     * @param  \App\Models\Page   $page
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
           $data = Page::findOrFail($id);
            return view('backend.superadmin.pages.show')->with('slider', $data);
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
     * @param  \App\Models\Page   $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        try {
           
                $data = Page::findOrFail(decrypt($id));
           
            return view('backend.superadmin.pages.edit')->with('page', $data);
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
     * @param  \App\Models\Page   $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'page_name' => 'required|min:1|max:198|unique:pages,page_name,' . $id,
                'slug' => 'required|min:1|max:198|unique:pages,slug,' . $id,
                'header_description' => 'required|min:3|max:30000',
                'footer_description' => 'required|min:3|max:30000',
                 'image' => 'required',
                
            ],
            [
                'short_description.required' => "The Short Description name field is required",
                'short_description.min' => "The Short Description Minimum Length 1",
                'short_description.max' => "The Short Description Maximum Length 190",
                'header_description.required' => "The Header Description name field is required",
                'header_description.min' => "The Header Description Minimum Length 1",
                'header_description.max' => "The Header Description Maximum Length 100000",
                'footer_description.required' => "The Footer Description name field is required",
                'footer_description.min' => "The Footer Description Minimum Length 1",
                'footer_description.max' => "The Footer Description Maximum Length 100000",

            ]
        );

        try {
            DB::beginTransaction();
            $page = Page::find($id);
            $page->superadmin_id = $this->User->id;
            $page->page_name = $request->page_name;
            $page->slug = Generate::Slug($request->slug);
            $page->meta_title = $request->meta_title ?: $request->name;
            $page->meta_description = $request->meta_description ?: $request->short_description;
            $page->json_screma = $request->json_screma;
            $page->keyword = $request->keyword;
            $page->header_description = $request->header_description;
            $page->footer_description = $request->footer_description;
            $page->image = $request->image;
            $page->save();
            DB::commit();
            Toastr::success("Page Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.pages.index');
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
     * @param  \App\Models\Page   $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page  $page)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $page = Page::findOrFail($request->id);
        $page->status = $request->status;
        if ($page->save()) {
            return 1;
        }
        return 0;
    }
}
