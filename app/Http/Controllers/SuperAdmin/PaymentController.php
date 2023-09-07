<?php
namespace App\Http\Controllers\Superadmin;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
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
            $User = $this->User;
            $data = Payment::latest();

            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.payments.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-edit"></i></a>';
                        $btn .= '</span>';
                        return $btn;
                    })
                    ->addColumn('image', function ($data) {
                        return '<a title="Click for View" data-lightbox="roadtrip" href="' . asset($data->image) . '"><img id="demo-test-gallery" class="border-radius-lg shadow demo-gallery" src="' . asset($data->image) . '" height="40px" width="40px"  />';

                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } else {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" checked="" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        }
                    })

                    ->rawColumns(['action','image', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.payments.index'), 'name' => "Payment"],
                ['name' => 'List'],
            ];
            return view('backend.superadmin.payments.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.payments.index'), 'name' => "Payment"],
            ['name' => 'Create'],
        ];
        return view('backend.superadmin.payments.create', compact('breadcrumbs'));
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
                'payment_name' => 'required|min:1|max:198|unique:payments',
                'status' => 'required',
                'image' => 'required',
                ],
            [
                'payment_name.required' => "The Payment name field is required",
                'payment_name.min' => "The Payment Minimum Length 1",
                'payment_name.max' => "The Payment Maximum Length 190",
                ]
        );

        try {
            DB::beginTransaction();
            $payment = new Payment();
            $payment->superadmin_id = $this->User->id;
            $payment->payment_name = $request->payment_name;
            $payment->image = $request->image;
            $payment->status = $request->status;
            $payment->save();
            DB::commit();

            Toastr::success("Payment Created Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.payments.index');
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
     * @param  \App\Models\Payment   $blog
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $User = $this->User;
            if ($User->user_type == 'Admin') {
                $data = Payment::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Payment::findOrFail($id);
            }
            return view('backend.common.payments.show')->with('slider', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function edit($id)
    {

        try {
            $data = Payment::findOrFail(decrypt($id));
           

            return view('backend.superadmin.payments.edit')->with('payment', $data);
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
     * @param  \App\Models\Payment   $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'payment_name' => 'required|min:1|max:198|unique:payments,payment_name,' . $id,
                'status' => 'required',
               
            ],
            [
                'payment_name.required' => "The Payment name field is required",
                'payment_name.min' => "The Payment Minimum Length 1",
                'payment_name.max' => "The Payment Maximum Length 190",

            ]
        );

        try {
            DB::beginTransaction();
            $payment = Payment::findOrFail($id);
            $payment->superadmin_id = $this->User->id;
            $payment->payment_name = $request->payment_name;
            $payment->image = $request->image;
            $payment->status = $request->status;
            $payment->save();
            DB::commit();
            Toastr::success("Payment Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.payments.index');
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
     * @param  \App\Models\Payment   $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment  $blog)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $payment = Payment::findOrFail($request->id);
        $payment->status = $request->status;
        if ($payment->save()) {
            return 1;
        }
        return 0;
    }
}
