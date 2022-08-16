<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;
use Laravel\Cashier\Cashier;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class StripePaymentController extends Controller
{
    /**
    * success response method.
    *
    * @return \Illuminate\Http\Response
    */
    public function stripe()
    {
        
        if (! auth()->user()->hasPermissionTo('Create Payment')) {
            abort(403);
        }

        return view('backend.payment.create');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        if (! auth()->user()->hasPermissionTo('Create Payment')) {
            abort(403);
        }

        Stripe\Stripe::setApiKey(fromSettings('stripe_secret')??env('STRIPE_SECRET'));

        try {
            DB::beginTransaction();
            $charge =  Stripe\Charge::create([
                "amount" => $request->amount*100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => 'Token = '.$request->stripeToken.',  Amount = '.$request->amount,
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


            // $response = Http::post('http://topifly.com/tfapi.php', [
            //     'Accion'=>'PoA',
            //     'useR' => $request->acc_no,
            //     'Amount' => $request->amount,
            //     'Approval'=>$charge->id
            // ]);
            // dd($response);

            DB::commit();
            alert()->success('Success', 'Payment Done Successfully');
            return redirect('http://topifly.com/tfapi.php?Accion=PoA&useR='.$request->acc_no.'&Amount='.$request->amount.'&Approval='.$charge->id);

            //return redirect(fromSettings('redirect_url')??'https://www.topifly.com/');
        } catch (\Throwable $th) {

            DB::rollback();
            Session::flash('error', $th->getMessage());
            alert()->error('Payment Unsuccessful!', $th->getMessage());
            return redirect()->back();
            // throw $th;
        }
    }


    public function customers(Request $request)
    {
        if (! auth()->user()->hasPermissionTo('Read Customers')) {
            abort(403);
        }
          // $from_date = $request->date_from;
        // $$request->date_to = $request->date_to ?? date('Y-m-d');
        // dd($request->date_to);

        $customers = Customer::
        when(!empty(request()->input('date_from') and request()->input('date_to') ), function ($q) {
            return $q->whereBetween('created_at', [request()->date_from, request()->date_to]);
        })
     
        ->when(!empty(request()->input('account_no')), function ($q) {
            return $q->where('account', '=', request()->input('account_no'));
        })
        ->when(!empty(request()->input('name')), function ($q) {
            return $q->where('name', '=', request()->input('name'));
        })
        ->when(!empty(request()->input('transaction_id')), function ($q) {
            return $q->where('transaction_id', '=', request()->input('transaction_id'));
        })
        ->when(!empty(request()->input('status')), function ($q) {
            return $q->where('status', '=', request()->input('status'));
        })
        ->orderBy('id', 'DESC')
        ->get();
        // dd($customers);
        return view('backend.customers.index')->withCustomers($customers);
    }
    public function customerShow($id)
    {
        if (! auth()->user()->hasPermissionTo('Read Customers')) {
            abort(403);
        }
        $customer = Customer::findOrFail($id);

        return view('backend.customers.show')
        ->withCustomer($customer);
    }

    public function processRefund($id, $trans)
    {
        if (! auth()->user()->hasPermissionTo('Update Customers')) {
            abort(403);
        }

        $customerid = $id;
        $customer = Customer::find($id);
        if ($customer->refund_id) {
            alert()->info('Info', 'This Transaction is Already Refunded');
            return redirect()->back();
        }
        $trans = $trans;
        return view('backend.customers.process_refund')
        ->withCustomerid($customerid)
        ->withTrans($trans);
    }
    public function processManualRefund($id, $trans)
    {  if (! auth()->user()->hasPermissionTo('Update Customers')) {
        abort(403);
    }
        $customerid = $id;
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
        if (! auth()->user()->hasPermissionTo('Update Customers')) {
            abort(403);
        }
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
        if (! auth()->user()->hasPermissionTo('Update Customers')) {
            abort(403);
        }

        $stripe =  Stripe\Stripe::setApiKey(fromSettings('stripe_secret')??env('STRIPE_SECRET'));

        try {
            $stripe = new \Stripe\StripeClient(fromSettings('stripe_secret')??env('STRIPE_SECRET'));
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


    public function testApi(Request $request){
        
      
       $response = Http::put('http://topifly.com/tfapi.php', [
                'Accion'=>'PoA',
                'useR' =>'2425568096',
                'Amount' => '30',
                'Approval'=>'43'
            ]);

        if ($response){
            return response()->json(['Resut'=>$response->getStatusCode()]);
        }
        else{
            return response()->json(['Resut'=>'Your post Request  is NOT saved Successfully']);

        }
    
    }
}
