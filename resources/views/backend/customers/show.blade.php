@extends('backend.main')
@section('title', 'User - FDD')

@section('styles')
@endsection

@push('css')
@endpush



@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                   <div class="iq-header-title">
                      <h4 class="card-title">Process Refund @if($customer->refund_id)<small> Refunded on :{{$customer->updated_at}}</small>@endif</h4>
                   </div>
                </div>
                <div class="iq-card-body px-4">
                   
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="first_name">Name</label>
                            <input type="text" class="form-control" name="name" disabled value="{{$customer->name}}">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="first_name">Transaction Date/Time</label>
                            <input type="text" class="form-control" name="name" disabled value="{{ date('d-m-y / H:i:s',strtotime($customer->created_at)) }}">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="last_name">Account</label>
                            <input type="text" class="form-control" name="account" disabled value="{{$customer->account}}">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="last_name">Amount</label>
                            <input type="text" class="form-control" name="account" disabled value="${{$customer->amount}}">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="last_name">Transaction ID</label>
                            <input type="text" class="form-control" name="account" disabled value="{{$customer->transaction_id}}">
                        </div>

                        
                     </div>
                  
                
                   
                      <div class="mt-5 mb-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h5>
                                        Charge Status:  <span class="ml-2 badge @if($customer->status=='Refunded')badge-danger @elseif($customer->status=='succeeded') badge-success @elseif($customer->status=='Manual-Refunded') badge-info @endif">{{$customer->status}}</span>
                                    </h5>
                                </div>
                            </div>
                           
                        </div>

                        @if ($customer->refund_id)
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h5>
                                        Refund ID:  <span class="ml-2 badge @if($customer->status=='Refunded')badge-danger @elseif($customer->status=='succeeded') badge-success @elseif($customer->status=='Manual-Refunded') badge-info @endif">{{$customer->refund_id}}</span>
                                    </h5>
                                </div>
                            </div> 
                        
                       
                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h5>
                                      Reason:  <span class="ml-2 badge @if($customer->status=='Refunded')badge-danger @elseif($customer->status=='succeeded') badge-success @elseif($customer->status=='Manual-Refunded') badge-info @endif">{{$customer->reason}}</span>
                                    </h5>
                                </div>
                            </div> 
                        
                       
                           
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h5>
                                      NOTE: <p >Payment refunded. It may take a few days for the money to reach the customer's bank account.
                                    </p>
                                    </h5>
                                </div>
                            </div> 
                        </div>
                        @endif
                      </div>
                      @can('Update Customers')
                      @if (!$customer->refund_id)

                      <a href="{{route('customers.processRefund',[$customer->id,$customer->transaction_id])}}" onclick="if (confirm('Are you Sure you want to Refund?')){return true;}else{event.stopPropagation(); event.preventDefault();};" class="btn btn-primary mr-3">Process Stripe Refund</a>
                      <a href="{{route('customers.manualRefundProcess',[$customer->id,$customer->transaction_id])}}" onclick="if (confirm('Are you Sure you want to Refund?')){return true;}else{event.stopPropagation(); event.preventDefault();};"  class="btn btn-primary mr-3">Process Manual Refund</a>
                      @endif
                      @endcan
  
                      <a href="{{route('customers.index')}}" class="btn iq-bg-danger mr-3">Back</a>

                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection


@section('scripts')
@endsection

@push('js')
@endpush
