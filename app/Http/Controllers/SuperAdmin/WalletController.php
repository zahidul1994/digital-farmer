<?php
namespace App\Http\Controllers\Superadmin;
use PDF;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Usernotification;


class WalletController extends Controller
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

        
    }
    public function index(Request $request)
    {

        try {
            $User = $this->User;
            $data = Wallet::with('user')->latest();
            if ($request->ajax()) {
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a href=' . route(request()->segment(1) . '.wallets.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 2px"><i class="fa fa-edit"></i></a>';
                        $btn .= '<a href=' . route(request()->segment(1) . '.wallets.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 2px" target="_blank"><i class="fas fa-file-pdf text-lg me-1"></i></a>';
                       $btn .= '</span>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } elseif($data->status == 1) {
                            return '<span class="badge badge-success badge-sm">Approve</span>';
                        }
                         else {
                            return '<span class="badge badge-danger badge-sm">Reject</span>';
                        }
                    })
                   
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.wallets.index'), 'name' => "Wallet"],
                ['name' => 'List'],
            ];
            return view('backend.superadmin.wallets.index', compact('breadcrumbs'));
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
            ['link' => route(request()->segment(1) . '.wallets.index'), 'name' => "Wallet"],
            ['name' => 'Create'],
        ];
        return view('backend.superadmin.wallets.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $paymentId=$request->payment_id;
       
        if($paymentId==1){
           //cash payment
            $this->validate($request,
            [
              'amount' => 'required|numeric|between:1,99999999',
              'invoice' => 'required|min:5|max:198',
              'admin_id' => 'required',
              'status' => 'required',
                'note' => 'max:500'],
            [
                
                'admin_id.required' => "The admin name field is required",
                
            ]);
         try {
            DB::beginTransaction();
            $cashpayment = new Wallet();
            $cashpayment->admin_id = $request->admin_id;
            $cashpayment->credit = $request->amount;
            $cashpayment->payment_id =$paymentId;
            $cashpayment->type = 'receive';
            $cashpayment->superadmin_id = $paymentId;
            $cashpayment->created_user_id = Auth::id();
            $cashpayment->updated_user_id =  Auth::id();
            $cashpayment->note = $request->note;
            $cashpayment->status = $request->status;
            $cashpayment->details = json_encode($request->invoice);
            $cashpayment->save();
            DB::commit();
            $userInfo=User::find($request->admin_id);
            $data = [
                'message' => 'Hi ' . $userInfo->name . ' . You Have Receive  a Payment  TK '.$request->amount ,

            ];
            $userInfo->notify(new Usernotification($data));

            Toastr::success("Cash Receive Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.wallets.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }

        }
        else{
            Toastr::error('Please Update Your package', "Error");
            return back();
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet   $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payments = Wallet::with('admin','payment')->findOrFail(decrypt($id));
        $pdf = PDF::loadView('backend.common.wallets.pdf',compact('payments'));
         return $pdf->stream('invoice_' . now() . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet   $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
           
            $data = Wallet::findOrFail(decrypt($id));
            return view('backend.superadmin.wallets.edit')->with('wallet', $data);
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
     * @param  \App\Models\Wallet   $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $paymentId=$request->payment_id;
       
        if($paymentId==1){
           //cash payment
            $this->validate($request,
            [
              'credit' => 'required|numeric|between:1,99999999',
              'invoice' => 'required|min:5|max:198',
              'admin_id' => 'required',
              'status' => 'required',
                'comment' => 'max:500'],
            [
                
                'admin_id.required' => "The admin name field is required",
                
            ]);
         try {
            DB::beginTransaction();
            $cashpayment = Wallet::find($id);
            $cashpayment->admin_id = $request->admin_id;
            $cashpayment->credit = $request->credit;
            $cashpayment->payment_id =$paymentId;
            $cashpayment->type = 'receive';
            $cashpayment->superadmin_id = $paymentId;
            $cashpayment->created_user_id = Auth::id();
            $cashpayment->updated_user_id =  Auth::id();
            $cashpayment->note = $request->note;
            $cashpayment->status = $request->status;
            $cashpayment->details = json_encode($request->invoice);
            $cashpayment->save();
            DB::commit();
            $userInfo=User::find($request->admin_id);
            $data = [
                'message' => 'Hi ' . $userInfo->name . ' . You Have Receive  a Payment Update At TK '.$request->credit ,

            ];
            $userInfo->notify(new Usernotification($data));

            Toastr::success("Cash Receive Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.wallets.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }

        }
        else{
            Toastr::error('Please Update Your package', "Error");
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet   $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet  $category)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $category = Wallet::findOrFail($request->id);
        $category->status = $request->status;
        if ($category->save()) {
            return 1;
        }
        return 0;
    }
}
