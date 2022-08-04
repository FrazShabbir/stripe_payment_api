<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;
use Laravel\Cashier\Cashier;
use App\Models\Customer;

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

        try {
            $charge =  Stripe\Charge::create([
                "amount" => $request->amount*100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from Stripe Payement  Gateway",
                "metadata" => [
                    "Account Number" => $request->acc_no,
                    'Name'=>$request->name
                    ]

            ]);
            $customer = Customer::create([
                'name'=>$request->name,
                'transaction_id'=>$charge->id,
                'amount'=>$request->amount,
                'account'=>$request->acc_no,
                'status'=>$charge->status,
            ]);
            $invoices = auth()->user()->invoices();

            $arr = [
                'id' => $charge->id,
                'charge' => $charge->amount,
                'last4' => $charge->payment_method_details->last4,
            ];
            //$user = '2425568096';
            //$url = "http://topifly.com/tfapi.php?Accion=PoA&useR=".$user."&Amount=".$request->amount."&Approval=".$charge->id;
            Session::flash('success', 'Payment successful!');
            //return redirect($url);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function customers()
    {
        $customers = Customer::all();
        return view('backend.customers.index')->withCustomers($customers);
    }
    public function refund($id)
    {
        $stripe =  Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $stripe->refunds->create([
                'charge' => $id,
              ]);
        } catch (\Throwable $th) {
            throw $th;
        }


        return redirect()->back();
    }
}
