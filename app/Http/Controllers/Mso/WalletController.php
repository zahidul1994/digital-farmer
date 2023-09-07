<?php
namespace App\Http\Controllers\Mso;
use PDF;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
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
            $data = Wallet::with('user')->whereuser_id(Auth::id())->latest();
            if ($request->ajax()) {
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<span class="badge badge-danger badge-sm">Pending</span>';
                        }
                        if ($data->status == 1) {
                            return '<span class="badge badge-success badge-sm">Approve</span>';
                        } 
                         else {
                            return '<span class="badge badge-danger badge-sm">Reject</span>';
                        }
                    })
                    ->addColumn('pdf', function ($data) {
                        $btn = '<a href=' . route(request()->segment(1) . '.downloadInvoice', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px" target="_blank"><i class="fas fa-file-pdf text-lg me-1"></i></a>';
                      return $btn;
                    })
                    ->rawColumns(['status','pdf'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.wallets.index'), 'name' => "Wallet"],
                ['name' => 'List'],
            ];
            return view('backend.common.wallets.index', compact('breadcrumbs'));
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
        return view('backend.admin.wallets.create', compact('breadcrumbs'));
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
          
            $this->validate($request,
            [
              'amount' => 'required|numeric|between:1,99999999',
              'invoice' => 'required|min:5|max:198',
              'receiver_id' => 'required',
              'note' => 'max:500'],
            [
                
                'receiver_id.required' => "The receiver  field is required",
                
            ]);
         try {
            DB::beginTransaction();
            $cashpayment = new Wallet();
            $cashpayment->receiver_id = $request->receiver_id;
            $cashpayment->user_id =  Auth::id();
            $cashpayment->credit = $request->amount;
            $cashpayment->payment_method =$paymentId;
            $cashpayment->type = 'receive';
            $cashpayment->invoice = $request->invoice;
            $cashpayment->created_user_id = Auth::id();
            $cashpayment->updated_user_id =  Auth::id();
            $cashpayment->note = $request->note;
            $cashpayment->save();
            DB::commit();
            $userInfo=User::find($request->receiver_id);
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
        try {
            $User = $this->User;
            if ($User->user_type == 'Admin') {
                $data = Wallet::whereuser_id($User->id)->findOrFail($id);
            } else {
                $data = Wallet::findOrFail($id);
            }
            return view('backend.common.wallets.show')->with('slider', $data);
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
     * @param  \App\Models\Wallet   $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
           
            $data = Wallet::findOrFail(decrypt($id));
            return view('backend.common.wallets.edit')->with('wallet', $data);
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
              'superadmin_id' => 'required',
              'status' => 'required',
                'comment' => 'max:500'],
            [
                
                'superadmin_id.required' => "The receiver  name field is required",
                
            ]);
         try {
            DB::beginTransaction();
            $cashpayment = Wallet::find($id);
            $cashpayment->admin_id = Auth::id();
            $cashpayment->credit = $request->credit;
            $cashpayment->payment_id =$paymentId;
            $cashpayment->type = 'receive';
            $cashpayment->superadmin_id = $request->superadmin_id;
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
    public function downloadInvoice($id)
    {     
       
        $payments = Wallet::with('admin','payment')->findOrFail(decrypt($id));
       $pdf = PDF::loadView('backend.common.wallets.pdf',compact('payments'));
        return $pdf->stream('invoice_' . now() . '.pdf');
    }
}
