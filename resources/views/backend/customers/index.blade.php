@extends('backend.main')
@section('title', 'All Customers')

@section('styles')
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4 mt-3">
                                        <h4>Filter By:</h4>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <form action="" method="">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="account_title">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        value="{{ app()->request->input('name') }}" placeholder="Name">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="account_title">Transaction ID</label>
                                                    <input type="text" class="form-control" id="transaction_id"
                                                        value="{{ app()->request->input('transaction_id') }}"
                                                        placeholder="Trans ID">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="account_title">Account Number</label>
                                                    <input type="number" class="form-control" id="account_no"
                                                        value="{{ app()->request->input('account_no') }}"
                                                        placeholder="Account no">
                                                </div>
                                            </div>
                                            {{-- Date With Date Range Picker --}}
                                            {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="date">Select Date</label>
                                                    <input type="text" name="daterange" class="form-control" id="date" value="01/01/2018 - 01/15/2018">
                                                 </div>
                                            </div> --}}
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="date_form">Date Range</label>
                                                    <input type="text" name="daterange" class="form-control"
                                                        id="delivery_date" value="{{ app()->request->input('date_from') }}-{{ app()->request->input('date_to') }}" />

                                                    {{-- <input type="date" name="date_form" class="form-control" id="date_from" value="{{ app()->request->input('date_from') }}"> --}}
                                                </div>
                                            </div>
                                          
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="account_title">Status</label>
                                                    <select name="" id="status" class="form-control">
                                                        <option value=" ">Select Status</option>
                                                        <option value="Refunded"
                                                            {{ app()->request->input('status') == 'Refunded' ? 'selected' : '' }}>
                                                            Refunded</option>
                                                        <option value="succeeded"
                                                            {{ app()->request->input('status') == 'succeeded' ? 'selected' : '' }}>
                                                            Succeeded</option>
                                                        <option value="Manual-Refunded"
                                                            {{ app()->request->input('status') == 'Manual-Refunded' ? 'selected' : '' }}>
                                                            Manual-Refunded</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="mb-5">
                                                    <button type="button" onclick="filter()"
                                                        class="btn btn-primary btn-sm mr-2">Apply Filter</button>
                                                    <button type="button" onclick="clearFilters()"
                                                        class="btn btn-outline-secondary btn-sm">Clear Filters</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>



                                </div>
                            </div>
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
                                                <td>{{ date('d-m-y / H:i:s', strtotime($customer->created_at)) }}</td>
                                                <td><span
                                                        class="ml-2 badge @if ($customer->status == 'Refunded') badge-danger @elseif($customer->status == 'succeeded') badge-success @elseif($customer->status == 'Manual-Refunded') badge-info @endif">{{ ucfirst($customer->status) }}</span>
                                                </td>

                                                <td>

                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top"
                                                            title="" data-original-title="Show"
                                                            href="{{ route('customers.show', $customer->id) }}"> <i
                                                                class="las la-eye"></i></a>

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
    <script>
        function filter() {
            const name = $("#name").val() ? $("#name").val() : "";
            const transaction_id = $("#transaction_id").val() ? $("#transaction_id").val() : "";
            const status = $("#status").val() ? $("#status").val() : "";

            const account_no = $("#account_no").val() ? $("#account_no").val() : "";
            // const date_from = $("#date_from").val() ? $("#date_from").val() : "";
            // const date_to = $("#date_to").val() ? $("#date_to").val() : "";
            const delivery_date = $("#delivery_date").val() ? $("#delivery_date").val() : "";
            const myArray = delivery_date.split(" - ");
            const date_from = myArray[0];
            const date_to = myArray[1];

// alert(date_to)
            const newurl = window.location.href.split("?");

            const url = newurl[0] + "?account_no=" + account_no + "&date_from=" + date_from +
                "&date_to=" + date_to + "&name=" + name + "&transaction_id=" + transaction_id + "&status=" + status;

            // alert(date_from);
            // console.log(completed_at);
            window.location.replace(url);
        }
    </script>
    <script>
        // alert('Final')
        function clearFilters() {
            const newurl = window.location.href.split("?");
            window.location.replace(newurl[0]);

        }
    </script>
@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#fdd-table').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
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
                            columns: [0, 1, 2, 3, 4]
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

        });
    </script>


    @if (app()->request->input('date_from'))
        <script>
            $(function() {
                $('#delivery_date').daterangepicker({
                    "startDate": "{{ app()->request->input('date_from') }} ",
                    "endDate": "{{ app()->request->input('date_to') }}",
                    locale: {
                        format: 'YYYY-MM-DD'
                    }

                });
            });
        </script>
    @else
        <script>
            $(function() {
                $('#delivery_date').daterangepicker({

                    locale: {
                        format: 'YYYY-MM-DD'
                    }

                });
            });
        </script>
    @endif
@endpush
