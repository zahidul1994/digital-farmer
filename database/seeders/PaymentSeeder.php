<?php

namespace Database\Seeders;
use App\Models\Payment;
use Illuminate\Database\Seeder;



class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $info = Payment::latest()->first();
        if (is_null($info)) {
            $paymentList = array(
                 'Cash',
                 'Bkash',
                 'Bank',
                 'Other',
            );
    
            foreach ($paymentList as $pay) {
                $payment = new Payment();
                $payment->payment_name = $pay;
                $payment->status = 1;
                $payment->superadmin_id = 1;
                $payment->save();
            }
    
            
        }
    }
}
