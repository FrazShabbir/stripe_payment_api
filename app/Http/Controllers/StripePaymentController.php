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
            // $invoices = auth()->user()->invoices();

            $arr = [
                'id' => $charge->id,
                'charge' => $charge->amount,
                'last4' => $charge->payment_method_details->last4,
            ];
            alert()->success('Success', 'Payment Done Successfully');


            return back();
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
            alert()->error('Payment Unsuccessful!', $th->getMessage());
            return redirect()->back();
            // throw $th;
        }
    }


    public function customers()
    {
        $customers = Customer::all();
        return view('backend.customers.index')->withCustomers($customers);
    }
    public function customerShow($id)
    {
        $customer = Customer::findOrFail($id);

        return view('backend.customers.show')
        ->withCustomer($customer);
    }

    public function processRefund($id, $trans)
    {
        $customerid = $id;
        $customer = Customer::find($id);
        if ($customer->refund_id) {
            alert()->info('Info', 'This Transaction  is Already Refunded');
            return redirect()->back();
        }
        $trans = $trans;
        return view('backend.customers.process_refund')
        ->withCustomerid($customerid)
        ->withTrans($trans);
    }
    public function processManualRefund($id, $trans)
    {$customerid = $id;
        $customer = Customer::find($id);
        if ($customer->refund_id) {
            alert()->info('Info', 'This Transaction  is Already Refunded');
            return redirect()->back();
        }
        $trans = $trans;
        return view('backend.customers.manualRefund')
        ->withCustomerid($customerid)
        ->withTrans($trans);
        
    }
    public function manualRefund(Request $request, $id, $trans)
    {
        $request->validate([
            'refund_id'=>'required',
            'reason'=>'required'
        ]);
        $customer = Customer::find($id);
        $customer->status = 'Manual-Refunded';
        $customer->refund_id = $request->refund_id;
        $customer->reason = $request->reason;
        $customer->save();
        alert()->success('Success', 'Refunded Manually Successfully');
        return redirect()->route('customers.index');
        }


    public function refund(Request $request, $id, $trans)
    {
        $stripe =  Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $refund = $stripe->refunds->create([
                'charge' => $trans,
              ]);
            $customer = Customer::find($id);
            $customer->status = 'Refunded';
            $customer->refund_id = $refund->id;
            $customer->reason = $request->reason;
            $customer->save();
            alert()->success('Success', 'Refunded Successfully');

            return redirect()->route('customers.index');
            //   dd($refund->status);
        } catch (\Throwable $th) {
            alert()->error($th->getMessage(), 'Error');

            return redirect()->route('customers.index');
            //throw $th;
        }
    }
}
