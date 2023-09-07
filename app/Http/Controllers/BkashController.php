<?php

namespace App\Http\Controllers;
use data;
use App\Model\Order;
use App\Model\SaleRecord;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\PaymentHistory;
use App\Model\MembershipPackage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Model\UserMembershipPackage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BkashController extends Controller
{
    private $base_url;

    public function __construct()
    {
        $url=URL::to('');
if($url=='https://dtpay.test'){
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

    public function pay(Request $request)
    {
    
        Session::put('bkash_amount',500);
       
        $token = $this->grant();
        Session::put('bkash_token', $token);
        return view('backend.admin.payments.bkash');

    }
    public function payCommission(Request $request,$id)
    {
        $saleRecord = SaleRecord::find($id);
        Session::put('payment_amount', getInvoiceWiseCommissionAmount($saleRecord->invoice_code));
        Session::put('type','commission');
        Session::put('sale_record',$saleRecord);
        $token = $this->grant();
        Session::put('bkash_token', $token);
        return view('frontend.seller.account.commission_pay',compact('saleRecord'));
    }

    //mark:pyamentid
    public function orderSummary($id){
        $order = Order::with('orderdetail')->find($id);
        Session::put('payment_amount', $order->grand_total);
        Session::put('type','order_payment');
        Session::put('order',$order);
        Session::put('order_id',$id);
        $token = $this->grant();
        Session::put('bkash_token', $token);
        return view('frontend.pages.order_summery',compact('order'));
    }

    public function create(Request $request)
    {
        $header =$this->authHeaders();

        $body_data = array(
            'amount' => Session::get('bkash_amount'),
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => "Inv".Str::random(20)
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/checkout/payment/create',$header,'POST',$body_data_json);
        //  Log::info($response);
        Session::put('paymentID', json_decode($response)->paymentID);

         $this->storeLog('/checkout/payment/create',$header,$body_data,$response);
        // commented out
        // your database operation
        return $response;
    }

    public function execute(Request $request)
    {
        $paymentID = Session::get('paymentID');

        $header =$this->authHeaders();

        $response = $this->curlWithoutBody('/checkout/payment/execute/'.$paymentID,$header,'POST');

        $arr = json_decode($response,true);

        if(array_key_exists("errorCode",$arr) && $arr['errorCode'] != '0000'){
            Session::put('errorMessage', $arr['errorMessage']);
        }else if(array_key_exists("message",$arr)){
            // if execute api failed to response
            sleep(1);
            $response = $this->queryIframe($paymentID);
        }

        Session::put('response',$response);

        $this->storeLog('/checkout/payment/execute/'.$paymentID,$header,$body_data = null,$response);

        // your database operation
        $bKashHistory = json_decode($response);
        if (Session::get('type') == 'package'){
            $membershipPackage = MembershipPackage::find(Session::get('membership_id'));
            $user_membership_package = new UserMembershipPackage();
            $user_membership_package->user_id = Auth::id();
            $user_membership_package->user_type = Auth::user()->user_type;
            $user_membership_package->membership_package_id = $membershipPackage->id;
            $user_membership_package->invoice_no = '';
            $user_membership_package->membership_activation_date = date('Y-m-d');
            $user_membership_package->membership_expired_date = date('Y-m-d', strtotime('+'.$membershipPackage->validation.' month'));;
            $user_membership_package->payment_status = 'Paid';
            $user_membership_package->sub_total = $membershipPackage->price;
            $user_membership_package->vat = vat($membershipPackage->price);
            $user_membership_package->vat_percentage = \App\Model\Vat::first()->vat_percentage;
            $user_membership_package->amount = $bKashHistory->amount;
            $user_membership_package->currency = 'BDT';
            $user_membership_package->payment_type = 'bKash';
            $user_membership_package->payment_id = $bKashHistory->paymentID;
            $user_membership_package->transaction_id = $bKashHistory->trxID;
            $user_membership_package->save();
            $user =Auth::user();
            $user->membership_package_id =$membershipPackage->id;
            $user->membership_activation_date = date('Y-m-d');
            $user->membership_expired_date = date('Y-m-d', strtotime('+1 year'));
            $user->save();

        }elseif (Session::get('type') == 'commission'){
            $bKashData['paymentID'] =$bKashHistory->paymentID;
            $bKashData['trxID'] =$bKashHistory->trxID;
            $bKashData['amount'] =$bKashHistory->amount;
            $bKashData['currency'] =$bKashHistory->currency;
            $bKashData['transactionStatus'] =$bKashHistory->transactionStatus;
            $saleRecord = Session::get('sale_record');
            $payment_history = new PaymentHistory();
            $payment_history->sale_record_id = $saleRecord->id;
            $payment_history->invoice_code = date('Ymd-his');
            $payment_history->user_id = Auth::id();
            $payment_history->user_type = Auth::user()->user_type;
            $payment_history->amount = $bKashHistory->amount;
            $payment_history->payment_status = 'Paid';
            $payment_history->payment_with = 'bKash';
            $payment_history->payment_type = 'bKash';
            $payment_history->description = NULL;
            $payment_history->currency = 'BDT';
            $payment_history->amount_after_getaway_fee = NULL;
            $payment_history->payment_details = json_encode($bKashData);
            $payment_history->date = date('Y-m-d');
            $payment_history->save();

            $saleRecord->payment_status = 'Paid';
            $saleRecord->save();
        }elseif (Session::get('type') == 'order_payment'){
            $bKashData['paymentID'] =$bKashHistory->paymentID;
            $bKashData['trxID'] =$bKashHistory->trxID;
            $bKashData['amount'] =$bKashHistory->amount;
            $bKashData['currency'] =$bKashHistory->currency;
            $bKashData['transactionStatus'] =$bKashHistory->transactionStatus;

            $order = Session::get('order');
            $order->payment_status = 'Paid';
            $order->payment_type = 'online';
            $order->payment_method = 'bkash';
            $order->payment_details = json_encode($bKashData);
            $order->save();
        }

        return $response;
    }
//zahidul create


public function ecommerceexecute(Request $request)
{
    $paymentID = Session::get('paymentID');
  
    $header =$this->authHeaders();

    $response = $this->curlWithoutBody('/checkout/payment/execute/'.$paymentID,$header,'POST');

    $arr = json_decode($response,true);

    if(array_key_exists("errorCode",$arr) && $arr['errorCode'] != '0000'){
        Session::put('errorMessage', $arr['errorMessage']);
    }else if(array_key_exists("message",$arr)){
        // if execute api failed to response
        sleep(1);
        $response = $this->queryIframe($paymentID);
    }

    Session::put('response',$response);

    $this->storeLog('/checkout/payment/execute/'.$paymentID,$header,$body_data = null,$response);
   
    // your database operation
    $bKashHistory = json_decode($response);
   
    $order = Order::findOrFail(Session::get('order_id'));
    $order->payment_status ="Paid";
    $order->payment_type = 'Bkash';
    $data['description'] = "Payment Complete with Bkash";
    $order->payment_method = json_encode($data);
    $order->save();
    return $response;
}






    public function queryIframe($paymentID){

        $header =$this->authHeaders();

        $response = $this->curlWithoutBody('/checkout/payment/query/'.$paymentID,$header,'GET');

        $this->storeLog('/checkout/payment/query/'.$paymentID,$header,$body_data = null,$response);

        return $response;
    }

    public function success(Request $request)
    {
        Toastr::success('Your bKash Payment Has Successfully Done.');
        if (Auth::user()->user_type == 'seller'){
            return redirect()->route('seller.dashboard');
        }elseif (Auth::user()->user_type == 'buyer'){
            return redirect()->route('buyer.dashboard');
        }else{
            return url('/');
        }
//        return view('Iframe.success')->with([
//            'response' => Session::get('response')
//        ]);
    }

    public function fail(Request $request)
    {
        return view('Iframe.fail')->with([
            'errorMessage' => Session::get('errorMessage')
        ]);
    }

    public function query(Request $request){
        return view('Iframe.query-payment');
    }

    public function queryPayment(Request $request){
        $paymentID = $request->paymentID;

        $token = $this->grant();
        Session::put('bkash_token', $token);

        $header =$this->authHeaders();

        $response = $this->curlWithoutBody('/checkout/payment/query/'.$paymentID,$header,'GET');

        $this->storeLog('/checkout/payment/query/'.$paymentID,$header,$body_data = null,$response);

        return view('Iframe.query-payment')->with([
            'response' => $response,
        ]);
    }

    public function search(Request $request){
        return view('Iframe.search-transaction');
    }

    public function searchTransaction(Request $request){
        $trxID = $request->trxID;

        $token = $this->grant();
        Session::put('bkash_token', $token);

        $header =$this->authHeaders();

        $response = $this->curlWithoutBody('/checkout/payment/search/'.$trxID,$header,'GET');

        $this->storeLog('/checkout/payment/search/'.$trxID,$header,$body_data = null,$response);

        return view('Iframe.search-transaction')->with([
            'response' => $response,
        ]);
    }

    public function getRefund(Request $request)
    {
        return view('Iframe.refund');
    }

    public function refund(Request $request)
    {
        $token = $this->grant();
        Session::put('bkash_token', $token);

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $request->paymentID,
            'amount' => $request->amount,
            'trxID' => $request->trxID,
            'sku' => 'sku',
            'reason' => 'Quality issue'
        );

        $body_data_json=json_encode($body_data);
        Session::put('paymentID',$request->paymentID);
        Session::put('trxID',$request->trxID);
        $response = $this->curlWithBody('/checkout/payment/refund',$header,'POST',$body_data_json);
        $arr = json_decode($response,true);
        if(array_key_exists("message",$arr)){
            // if execute api failed to response
            sleep(1);

            $response = $this->refundIframe(Session::get('paymentID'), Session::get('trxID'));
        }
        $this->storeLog('/checkout/payment/refund',$header,$body_data,$response);

        // your database operation
        return view('Iframe.refund')->with([
            'response' => $response,
        ]);
    }

    public function getRefundStatus(Request $request)
    {
        return view('Iframe.refund-status');
    }

    public function refundStatus(Request $request)
    {
        $token = $this->grant();
        Session::put('bkash_token', $token);

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $request->paymentID,
            'trxID' => $request->trxID,
        );
        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/checkout/payment/refund',$header,'POST',$body_data_json);

        $this->storeLog('/checkout/payment/refund-status',$header,$body_data,$response);


        return view('Iframe.refund-status')->with([
            'response' => $response,
        ]);
    }
    public function refundIframe($paymentId,$trxID)
    {
//        $token = $this->grant();
//        Session::put('bkash_token', $token);

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentId,
            'trxID' => $trxID,
        );
        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/checkout/payment/refund',$header,'POST',$body_data_json);

        $this->storeLog('/checkout/payment/refund-status',$header,$body_data,$response);


        return $response;
    }






}
