<?php
namespace App\Http\Controllers;

use App\Model\PaymentHistory;
use App\Model\SaleRecord;
use App\Model\SmsCostPaymentHistory;
use App\Model\SSLCommerzModel;
use App\Model\UserMembershipPackage;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

session_start();
class PublicSslCommerzPaymentController extends Controller
{
    public function index(Request $request)
    {
       
        $user_type = Session::get('user_type');
        $current_table_name = Session::get('current_table_name');

        if($current_table_name == 'user_membership_packages'){
            $order =  UserMembershipPackage::find(Session::get('user_membership_package_id'));
        }elseif($current_table_name == 'payment_histories'){
            $order =  PaymentHistory::find(Session::get('payment_history_id'));
        }elseif($current_table_name == 'sms_cost_payment_histories'){
            $order =  SmsCostPaymentHistory::find(Session::get('sms_cost_payment_history_id'));
        }else{
            dd('something went wrong');
        }

        $post_data = array();
        $post_data['total_amount'] = $order->amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid('', true); // tran_id must be unique

        $order->currency = $post_data['currency'];
        $order->transaction_id = $post_data['tran_id'];
        $order->ssl_status = 'Pending';
        $order->save();

        // custom save dynamic table
        $modeSave = new SSLCommerzModel();
        $modeSave->user_type = $user_type;
        $modeSave->ssl_encrypted_text = encrypt(Session::get('password'));
        $modeSave->order_id = $order->id;
        $modeSave->tran_id = $post_data['tran_id'];
        $modeSave->current_table_name = $current_table_name;
        $modeSave->save();
        // custom save dynamic table

        $_SESSION['payment_values']['tran_id']=$post_data['tran_id'];
        $server_name=$request->root()."/";
        $post_data['success_url'] = $server_name . "success";
        $post_data['fail_url'] = $server_name . "fail";
        $post_data['cancel_url'] = $server_name . "cancel";

        #Before  going to initiate the payment order status need to update as Pending.
        if($current_table_name == 'user_membership_packages') {
            DB::table('user_membership_packages')
                ->where('transaction_id', $post_data['tran_id'])
                ->update(['ssl_status' => 'Pending', 'currency' => $post_data['currency']]);
        }elseif($current_table_name == 'payment_histories'){
            DB::table('payment_histories')
                ->where('transaction_id', $post_data['tran_id'])
                ->update(['ssl_status' => 'Pending','currency' => $post_data['currency']]);
        }elseif($current_table_name == 'sms_cost_payment_histories'){
            DB::table('sms_cost_payment_histories')
                ->where('transaction_id', $post_data['tran_id'])
                ->update(['ssl_status' => 'Pending','currency' => $post_data['currency']]);
        }else{
            dd('something went wrong');
        }
        $sslc = new SSLCommerz();
//        dd('sayka');
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->initiate($post_data, false);
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }
    public function success(Request $request)
    {
        $sslc = new SSLCommerz();
        $tran_id = $request->tran_id;
        $get_current_table_name = SSLCommerzModel::where('tran_id',$tran_id)->pluck('current_table_name')->first();
        if($get_current_table_name == 'user_membership_packages'){
            $order_detials = DB::table('user_membership_packages')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            $chekTotal = $order_detials->amount;
            if($order_detials->ssl_status=='Pending')
            {

                $validation = $sslc->orderValidate($tran_id, $chekTotal, $order_detials->currency, $request->all());
                if($validation == TRUE)
                {
                    $order=UserMembershipPackage::where('transaction_id',$tran_id)->first();
                    $order->ssl_status='Completed';
                    $order->payment_status='Paid';
                    $order->amount_after_getaway_fee=$_POST['store_amount'];
                    $order->payment_details=json_encode($_POST);
                    $order->update();

                    $user = User::find($order->user_id);
                    $user->membership_package_id = $order->membership_package_id;
                    $user->membership_activation_date = $order->membership_activation_date;
                    $user->membership_expired_date = $order->membership_expired_date;
                    $user->save();

                    //Toastr::success('Transaction is successfully Completed','Success');
                    //return back();
                    return redirect('ssl/redirect/'.$tran_id);
                }
                else
                {
                    DB::table('user_membership_packages')
                        ->where('transaction_id', $tran_id)
                        ->update(['ssl_status' => 'Failed']);
                    echo "validation Fail";
                }
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is successfully Completed tar','Success');
                return back();
            }
            else
            {
                Toastr::error('Invalid Transaction ','Error');
                return back();
            }
        }elseif($get_current_table_name == 'payment_histories'){
            $order_detials = DB::table('payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            $chekTotal = $order_detials->amount;
            if($order_detials->ssl_status=='Pending')
            {

                $validation = $sslc->orderValidate($tran_id, $chekTotal, $order_detials->currency, $request->all());
                if($validation == TRUE)
                {
                    $order=PaymentHistory::where('transaction_id',$tran_id)->first();
                    $order->ssl_status='Completed';
                    $order->payment_status='Paid';
                    $order->payment_type=$_POST['card_type'];
                    $order->payment_details=json_encode($_POST);
                    $order->update();

                    $sale_recode = SaleRecord::find($order->sale_record_id);
                    $sale_recode->payment_status = 'Paid';
                    $sale_recode->save();

//                    Toastr::success('Transaction is successfully Completed','Success');
                    return redirect('ssl/redirect/'.$tran_id);
                }
                else
                {
                    DB::table('payment_histories')
                        ->where('transaction_id', $tran_id)
                        ->update(['ssl_status' => 'Failed']);

                    Toastr::warning('validation Fail','Warning');
                    return redirect('seller/accounts');
                }
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is successfully Completed tar','Success');
                return redirect('seller/accounts');
            }
            else
            {
                Toastr::error('Invalid Transaction ','Error');
                return redirect('seller/accounts');
            }
        }elseif($get_current_table_name == 'sms_cost_payment_histories'){
            $order_detials = DB::table('sms_cost_payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            $chekTotal = $order_detials->amount;
            if($order_detials->ssl_status=='Pending')
            {

                $validation = $sslc->orderValidate($tran_id, $chekTotal, $order_detials->currency, $request->all());
                if($validation == TRUE)
                {
                    $order=SmsCostPaymentHistory::where('transaction_id',$tran_id)->first();
                    $order->ssl_status='Completed';
                    $order->payment_status='Paid';
                    $order->payment_type=$_POST['card_type'];
                    $order->payment_details=json_encode($_POST);
                    $order->update();

//                    Toastr::success('Transaction is successfully Completed','Success');
                    return redirect('ssl/redirect/'.$tran_id);
                }
                else
                {
                    DB::table('sms_cost_payment_histories')
                        ->where('transaction_id', $tran_id)
                        ->update(['ssl_status' => 'Failed']);
                    echo "validation Fail";
                }
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is successfully Completed tar','Success');
                return redirect('seller/accounts');
            }
            else
            {
                Toastr::error('Invalid Transaction ','Error');
                return back();
            }
        }else{
            dd('something went wrong');
        }

    }
    public function fail(Request $request)
    {
        $tran_id = $_SESSION['payment_values']['tran_id'];
        $get_current_table_name = SSLCommerzModel::where('tran_id',$tran_id)->pluck('current_table_name')->first();
        if($get_current_table_name == 'user_membership_packages'){
            $order_detials = DB::table('user_membership_packages')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            if($order_detials->order_status=='Pending')
            {
                DB::table('user_membership_packages')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Failed']);
                Toastr::error('Transaction is Falied','Error');
                return back();
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is already Successful','Success');
                return back();
            }
            else
            {
                Toastr::error('Transaction is Invalid','Error');
                return back();
            }
        }elseif($get_current_table_name == 'payment_histories'){
            $order_detials = DB::table('payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            if($order_detials->order_status=='Pending')
            {
                DB::table('payment_histories')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Failed']);
                Toastr::error('Transaction is Falied','Error');
                return back();
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is already Successful','Success');
                return back();
            }
            else
            {
                Toastr::error('Transaction is Invalid','Error');
                return back();
            }
        }elseif($get_current_table_name == 'sms_cost_payment_histories'){
            $order_detials = DB::table('sms_cost_payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            if($order_detials->order_status=='Pending')
            {
                DB::table('sms_cost_payment_histories')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Failed']);
                Toastr::error('Transaction is Falied','Error');
                return back();
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is already Successful','Success');
                return back();
            }
            else
            {
                Toastr::error('Transaction is Invalid','Error');
                return back();
            }
        }else{
            dd('something went wrong');
        }


    }
    public function cancel(Request $request)
    {
        $tran_id = $_SESSION['payment_values']['tran_id'];
        $get_current_table_name = SSLCommerzModel::where('tran_id',$tran_id)->pluck('current_table_name')->first();
        if($get_current_table_name == 'user_membership_packages') {
            $order_detials = DB::table('user_membership_packages')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status', 'currency', 'amount')->first();
            if ($order_detials->ssl_status == 'Pending') {
                DB::table('user_membership_packages')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Canceled']);
                Toastr::error('Transaction is Cancel', 'Error');
                return back();
            } else if ($order_detials->ssl_status == 'Processing' || $order_detials->ssl_status == 'Complete') {
                Toastr::success('Transaction is already Successful', 'Success');
                return back();
            } else {
                Toastr::error('Transaction is Invalid', 'Error');
                return back();
            }
        }elseif($get_current_table_name == 'payment_histories'){
            $order_detials = DB::table('payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            if($order_detials->ssl_status=='Pending')
            {
                DB::table('payment_histories')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Canceled']);
                Toastr::error('Transaction is Cancel','Error');
                return back();
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is already Successful','Success');
                return back();
            }
            else
            {
                Toastr::error('Transaction is Invalid','Error');
                return back();
            }
        }elseif($get_current_table_name == 'sms_cost_payment_histories'){
            $order_detials = DB::table('sms_cost_payment_histories')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','amount')->first();
            if($order_detials->ssl_status=='Pending')
            {
                DB::table('sms_cost_payment_histories')
                    ->where('transaction_id', $tran_id)
                    ->update(['ssl_status' => 'Canceled']);
                Toastr::error('Transaction is Cancel','Error');
                return back();
            }
            else if($order_detials->ssl_status=='Processing' || $order_detials->ssl_status=='Complete')
            {
                Toastr::success('Transaction is already Successful','Success');
                return back();
            }
            else
            {
                Toastr::error('Transaction is Invalid','Error');
                return back();
            }
        }else{
            dd('something went wrong');
        }

    }
    public function ipn(Request $request)
    {
        if($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'ssl_status','currency','grand_total')->first();
            if($order_details->ssl_status =='Pending')
            {
                $sslc = new SSLCommerz();
                $validation = $sslc->orderValidate($tran_id, $order_details->grand_total, $order_details->currency, $request->all());
                if($validation == TRUE)
                {
                    DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['ssl_status' => 'Processing']);

                    echo "Transaction is successfully Complete";
                }
                else
                {
                    DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['ssl_status' => 'Failed']);

                    echo "validation Fail";
                }

            }
            else if($order_details->ssl_status == 'Processing' || $order_details->ssl_status =='Complete')
            {
                echo "Transaction is already successfully Complete";
            }
            else
            {
                echo "Invalid Transaction";
            }
        }
        else
        {
            echo "Inavalid Data";
        }
    }

    public function status($tran_id)
    {
        $get_current_table_name = SSLCommerzModel::where('tran_id',$tran_id)->select('current_table_name','ssl_encrypted_text')->first();
        if($get_current_table_name->current_table_name == 'user_membership_packages'){
            $user_info = DB::table('user_membership_packages')
                ->join('users','user_membership_packages.user_id','users.id')
                ->where('user_membership_packages.transaction_id', $tran_id)
                ->select('users.phone')->first();

        }elseif($get_current_table_name->current_table_name == 'payment_histories'){
            $user_info = DB::table('payment_histories')
                ->join('users','payment_histories.user_id','users.id')
                ->where('payment_histories.transaction_id', $tran_id)
                ->select('users.phone')->first();

        }elseif($get_current_table_name->current_table_name == 'sms_cost_payment_histories'){
            $user_info = DB::table('sms_cost_payment_histories')
                ->join('users','sms_cost_payment_histories.user_id','users.id')
                ->where('sms_cost_payment_histories.transaction_id', $tran_id)
                ->select('users.phone')->first();

        }else{
            dd('something went wrong');
        }


        $credentials = [
//            'phone' => '1922088046',
//            'password' => '123456'
            'phone' => $user_info->phone,
            'password' => decrypt($get_current_table_name->ssl_encrypted_text)
        ];
        if(Auth::attempt($credentials)){

            if (Auth::check() && Auth::user()->user_type == 'buyer') {
                return redirect()->route('buyer.dashboard');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'seller' && Auth::user()->seller->employer_status == 0) {
                return redirect()->route('seller.dashboard');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'seller' && Auth::user()->seller->employer_status == 1) {
                return redirect()->route('employer-dashboard');
            }
            else {
                return('/');
            }
        }else{
            dd('Credentials Problem!');
        }
    }
}
