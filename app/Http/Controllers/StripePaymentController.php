<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;
use Laravel\Cashier\Cashier;

class StripePaymentController extends Controller
{
    
     /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('backend.dashboard.dashboard');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // $user = Cashier::findBillable($stripeId);
        // $stripeCustomer = $user->createAsStripeCustomer();

        // $stripeCustomer = $user->asStripeCustomer();
        // $stripeCustomer = $user->createOrGetStripeCustomer();


        // Stripe\StripeClient::create([
        //     'name'=>'Fraz Shabbir',
        //     'description' => 'My First Test Customer (created for API docs at https://www.stripe.com/docs/api)',
        //     'metadata'=>[
        //         'account'=>'23456789',
        //         ]   
        //   ]);

       $charge =  Stripe\Charge::create ([
                "amount" => $request->amount*100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from Stripe Payement  Gateway",
                "metadata" => ["Account Number" => $request->acc_no]

        ]);
$arr = [
    'id' => $charge->id,
    'charge' => $charge->amount,
    'last4' => $charge->payment_method_details->last4,
];
     dd($arr);

        Session::flash('success', 'Payment successful!');
          
        return back();
    }

}
