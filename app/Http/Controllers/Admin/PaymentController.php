<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Branch;
use App\Helpers\Helper;
use App\Models\Wallet;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Notifications\Usernotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
   
    private $base_url;

    public function __construct()
    {
        $url=URL::to('');
if($url=='http://agency.test'){
    $this->base_url = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
}
else{
    $this->base_url = 'https://checkout.pay.bka.sh/v1.2.0-beta';
}
       
          
    }

    public function authHeaders(){
        return array(
            'Content-Type:application/json',
            'Authorization:' .Session::get('bkash_token'),
            'X-APP-Key:'.env('BKASH_CHECKOUT_APP_KEY')
        );
    }

    public function curlWithBody($url,$header,$method,$body_data_json){
        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function curlWithoutBody($url,$header,$method){
        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function storeLog($url,$header,$body_data,$response){
        $log_data=["url"=>$this->base_url.$url,"header"=>$header,"body"=> $body_data,"api response"=>json_decode($response)];
        return Log::channel('bkash')->info($log_data);
    }

    public function grant()
    {
        $header = array(
            'Content-Type:application/json',
            'username:'.env('BKASH_CHECKOUT_USER_NAME'),
            'password:'.env('BKASH_CHECKOUT_PASSWORD')
        );
        // dd($header);
        $header_data_json=json_encode($header);

        $body_data = array('app_key'=> env('BKASH_CHECKOUT_APP_KEY'), 'app_secret'=>env('BKASH_CHECKOUT_APP_SECRET'));

        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/checkout/token/grant',$header,'POST',$body_data_json);
        // dd($response);
        $test = json_decode($response,true);
        $token = $test['id_token'];

         $this->storeLog('/checkout/token/grant',$header,$body_data,$response);
        // commented out

        return $token;
    }
    public function index(Request $request)
    {
       
        try {
           
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.payments'), 'name' => "Payment"],
                ['name' => 'List'],
            ];
         $userInfo=User::with('profile')->find(Auth::id());
         $payentmethod=Payment::wherestatus(1)->get();
         $wallets=Wallet::whereadmin_id(Auth::id())->get();
         $approvepayment=Wallet::whereadmin_id(Auth::id())->wherestatus(1)->orderBy('created_at')->get(['credit','created_at'])
         ->groupBy(function ($date)
          {return $date->created_at->month;
         })
         ->map(function ($group) {
             return $group->sum('credit');
         })->union(array_fill(1, 12, 0))
         ->sortKeys()
         ->toArray();
         $pendingpayment=Wallet::whereadmin_id(Auth::id())->wherestatus(0)->orderBy('created_at')->get(['credit','created_at'])
         ->groupBy(function ($date)
          {return $date->created_at->month;
         })
         ->map(function ($group) {
            return $group->sum('credit');
         })->union(array_fill(1, 12, 0))
         ->sortKeys()
         ->toArray();
            return view('backend.admin.payments.index',compact('breadcrumbs','userInfo','payentmethod','wallets','approvepayment','pendingpayment'));
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
    public function create(Request $request)
    {
        $this->validate($request, [
           'amount' => 'required|min:1|max:40',
            ]);

        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.payments'), 'name' => "Payment"],
            ['name' => 'List'],
        ];
     
         $id=$request->payment;
         
         Session::put('paymentById', $id);
         Session::put('paymentAmount', $request->amount);
        if($id==1){
            // cash
            return view('backend.admin.payments.cash',compact('breadcrumbs'));
        }
        if($id==2){
            Session::put('bkash_amount',$request->amount);
            $token = $this->grant();
            Session::put('bkash_token', $token);
            return view('backend.admin.payments.bkash');
            // return Redirect::to('pay');
        }
        return view('backend.admin.payments.create',compact('breadcrumbs'));
   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $sessionId= Session::get('paymentById');
        if($sessionId==1){
           //cash payment
            $this->validate($request,
            [
              'amount' => 'required|numeric|between:1,99999999',
              'invoice' => 'required|min:5|max:198',
              'receiver_id' => 'required',
                'comment' => 'max:500'],
            [
                
                'receiver_id.required' => "The receiver name field is required",
                
            ]);
        try {
            DB::beginTransaction();
            $cashpayment = new Wallet();
            $cashpayment->admin_id = Auth::id();
            $cashpayment->credit = $request->amount;
            $cashpayment->payment_id =  Session::get('paymentById');
            $cashpayment->type = 'payment';
            $cashpayment->superadmin_id = $request->receiver_id;
            $cashpayment->created_user_id = Auth::id();
            $cashpayment->updated_user_id =  Auth::id();
            $cashpayment->note = $request->note;
            $cashpayment->details = json_encode($request->invoice);
            $cashpayment->save();
            DB::commit();
            $data = [
                'message' => 'Hi ' . Auth::user()->name . ' . Create a Payment Request TK '.$request->amount ,

            ];
            User::first()->notify(new Usernotification($data));

            Toastr::success("Cash Payment Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.payments');
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
     * @param  \App\Models\Branch   $branch
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch   $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        try {
           
            
                $data = Branch::whereadmin_id(Auth::id())->findOrFail(decrypt($id));
            
                
            return view('backend.admin.branchs.edit')->with('branch', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function update(Request $request, $id)
    { 
        $this->validate($request,
        [
          'branch_phone' => 'required|min:9|max:11',
          'branch_address' => 'required|min:9|max:198',
          'status' => 'required|min:0|max:190',
            'branch_name' => ['required','min:1',
            'max:198', Rule::unique('branches')->ignore($id, 'id')->where(function ($query) {
                    return $query->where('admin_id', Auth::id());
                })
            ]               
            ],
        [
            'branch_name.unique' => "The Branch name field need to be unique",
            'branch_name.required' => "The Branch name field is required",
            'branch_name.min' => "The Branch Minimum field length 1",
            'branch_name.max' => "The Branch Maximum field length 100",
        ]);
        try {
            DB::beginTransaction();
            $branch = Branch::find($id);
            $branch->branch_name = $request->branch_name;
            $branch->branch_phone = $request->branch_phone;
            $branch->branch_email = $request->branch_email;
            $branch->branch_address = $request->branch_address;
            $branch->admin_id = $this->User->id;
            $branch->updated_user_id = $this->User->id;
            $branch->status = $request->status;
           $branch->save();
            DB::commit();
           Toastr::success("Branch Update Successfully", "Success");
            return redirect()->route(request()->segment(1) . '.branchs.index');
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
     * @param  \App\Models\Branch   $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch  $branch)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $branch = Branch::findOrFail($request->id);
        $branch->status = $request->status;
        $branch->updated_user_id = Auth::id();
        if ($branch->save()) {
            return 1;
        }
        return 0;
    }
}
