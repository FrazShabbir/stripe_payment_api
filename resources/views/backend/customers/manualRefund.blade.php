@extends('backend.main')
@section('title', 'Process Refund')

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
                      <h4 class="card-title">Process Refund</h4>
                   </div>
                </div>
                <div class="iq-card-body px-4">
                   <form action="{{route('customers.manualRefundProcess',[$customerid,$trans])}}" method="POST">
                    @csrf
                    {{@method_field('POST')}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="reason" class="required">Reason</label>
                            <input type="text" class="form-control" name="reason" placeholder="State Your Reason">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                           <label for="reason" class="required">Refund ID</label>
                           <input type="text" class="form-control" name="refund_id" placeholder="Enter Your Systems REFUND ID">
                       </div>
                       
                     </div>
                   
                      <button type="submit" class="btn btn-primary mr-3">Update Data</button>
                      <a href="{{route('users.index')}}" class="btn iq-bg-danger mr-3">Cancel</a>
                   </form>
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
