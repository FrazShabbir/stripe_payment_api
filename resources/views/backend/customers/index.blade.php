@extends('backend.main')
@section('title', 'All Customers')

@section('styles')
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
@endpush



@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Customers</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                        
                                <table id="fdd-table" class="table table-striped table-bordered mt-4" role="grid"
                                    aria-describedby="user-list-page-info">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Name</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>Date/Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td>{{ $customer->transaction_id }}</td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->account }}</td>
                                                <td>${{ $customer->amount }}</td>
                                                <td>{{ date('d-m-y / H:i:s',strtotime($customer->created_at)) }}</td>
                                                <td><span class="ml-2 badge @if($customer->status=='Refunded')badge-danger @elseif($customer->status=='succeeded') badge-success @elseif($customer->status=='Manual-Refunded') badge-info @endif" >{{ ucfirst($customer->status) }}</span></td>
                                               
                                                <td>
                                                   
                                                        <div class="flex align-items-center list-user-action">
                                                            <a class="iq-bg-primary" data-toggle="tooltip"
                                                                data-placement="top" title=""
                                                                data-original-title="Show" href="{{route('customers.show', $customer->id)}}"> <i class="las la-eye"></i></a>
                                            
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>

                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                       
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
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>


<script>
    $(document).ready( function () {
        $('#fdd-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4 ]
                }
            },
            'colvis'
        ],
            // "searching": false,
            // "paging": false,
            "info": false,
            "lengthChange": false,

        });
        $('#fdd-table_paginate ul').addClass("pagination-sm");

    } );
</script>

@endpush
