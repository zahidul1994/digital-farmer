<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CheckoutController;
use App\Model\PaymentHistory;
use App\Model\SmsCostPaymentHistory;
use App\Model\UserMembershipPackage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Stripe;
use App\Order;
use App\BusinessSetting;
use App\Seller;
use Session;
use App\CustomerPackage;
use App\SellerPackage;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\WalletController;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        //dd(Session::get('user_membership_package_id'));
        $membership_package = UserMembershipPackage::findOrFail(Session::get('user_membership_package_id'));
        return view('frontend.payment.stripe', compact('membership_package'));
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    function stripePost(Request $request)
    {
        if($request->session()->has('payment_type')){
            if($request->session()->get('payment_type') == 'cart_payment'){
                $userMembershipPackage = UserMembershipPackage::findOrFail(Session::get('user_membership_package_id'));

                //Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                //Stripe\Stripe::setApiKey('sk_test_9IMkiM6Ykxr1LCe2dJ3PgaxS');
                Stripe\Stripe::setApiKey('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');
                //dd($request->stripeToken);
                try {
                    $payment = json_encode(Stripe\Charge::create ([
                            //"amount" => round(convert_to_usd($userMembershipPackage->amount) * 100),
                            "amount" => $userMembershipPackage->amount,
                            "currency" => "usd",
                            "source" => $request->stripeToken
                    ]));
                } catch (\Exception $e) {
                    Toastr::warning($e->getMessage());
                    return redirect()->back();
                    //return redirect()->route('checkout.payment_info');
                }

                $checkoutController = new CheckoutController();
                return $checkoutController->checkout_done($request->session()->get('user_membership_package_id'), $payment);
            }
        }else{
            Toastr::warning('Something Went Wrong!');
            return redirect()->back();
        }
    }

    function stripe2(){
        $payment_history = PaymentHistory::findOrFail(Session::get('payment_history_id'));
        return view('frontend.payment.stripe', compact('payment_history'));
    }

    function stripe2Post(Request $request){
        $paymentHistory = PaymentHistory::findOrFail(Session::get('payment_history_id'));

        //Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        Stripe\Stripe::setApiKey('sk_test_9IMkiM6Ykxr1LCe2dJ3PgaxS');
        Stripe\Stripe::setApiKey('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');
        try {
            $payment = json_encode(Stripe\Charge::create ([
                //"amount" => round(convert_to_usd($userMembershipPackage->amount) * 100),
                "amount" => $paymentHistory->amount * 100,
                //"amount" => $paymentHistory->amount,
                "currency" => "usd",
                "source" => $request->stripeToken
            ]));
        } catch (\Exception $e) {
            dd('Something Went Wrong!');
            Toastr::warning($e->getMessage());
            return redirect()->back();
        }

        $checkout2Controller = new CheckoutController();
        return $checkout2Controller->checkout2_done($request->session()->get('payment_history_id'), $payment);
    }

    function stripe3(){
        $sms_cost_payment_history = SmsCostPaymentHistory::findOrFail(Session::get('sms_cost_payment_history_id'));
        return view('frontend.payment.stripe', compact('sms_cost_payment_history'));
    }

    function stripe3Post(Request $request){
        dd($request->all());
        $smsCostPaymentHistory = SmsCostPaymentHistory::findOrFail(Session::get('sms_cost_payment_history_id'));

        //Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        Stripe\Stripe::setApiKey('sk_test_9IMkiM6Ykxr1LCe2dJ3PgaxS');
        Stripe\Stripe::setApiKey('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');
        try {
            $payment = json_encode(Stripe\Charge::create ([
                //"amount" => round(convert_to_usd($userMembershipPackage->amount) * 100),
                //"amount" => $paymentHistory->amount * 100,
                "amount" => $smsCostPaymentHistory->amount,
                "currency" => "usd",
                "source" => $request->stripeToken
            ]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage());
            return redirect()->back();
        }

        $checkout3Controller = new CheckoutController();
        return $checkout3Controller->checkout3_done($request->session()->get('sms_cost_payment_history_id'), $payment);
    }
}
