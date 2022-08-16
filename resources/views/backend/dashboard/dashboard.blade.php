@extends('backend.main')
@section('title', 'Make A payment')

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
                                <h4 class="card-title">Stats</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="card-title">Customers By Month</h4>
                                    <div class="widget-area-2 HealthPortal-PK-box-shadow">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="card-title">Transactions Amounts</h4>
                                    <div class="widget-area-2 HealthPortal-PK-box-shadow">
                                        <canvas id="myChart2"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @can('Read Users')
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Recent Transactions</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">    
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
                @endcan
            
            </div>
        </div>
    </div>



@endsection


@section('scripts')

@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var ctx = document.getElementById("myChart").getContext("2d");
        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(1, '#ECD293');
        gradient = ctx.createLinearGradient(0, 0, 0, 450);
        gradient.addColorStop(0, 'rgba(245, 222, 167, 0.79)');
        gradient.addColorStop(0.4, 'rgba(255, 234, 185, 0)');
        gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
        var months = {{ $months }};
        var numberOfCustomers = {{ $numberOfCustomers }}
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    borderColor: gradientStroke,
                    pointBorderColor: gradientStroke,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    borderWidth: 1,
                    backgroundColor: gradient,
                    pointBorderColor: "#fff",
                    fill: true,

                    label: 'Customers',
                    data: numberOfCustomers,

                }]
            },
            options: {
                elements: {
                    point: {
                        display: true,
                        radius: 1
                    }
                },
                scales: {

                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: true

                        },
                        ticks: {
                            display: true
                        },
                        gridLines: {
                            display: true,
                            drawBorder: true,
                        }

                    },
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: true
                        },

                        ticks: {
                            display: true
                        }

                    },

                },


                plugins: {
                    legend: {
                        display: true,
                        drawBorder: false
                    }
                },

            }
        });
    </script>
    <script>
        var ctx = document.getElementById("myChart2").getContext("2d");
        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(1, '#4859AD');


        gradient = ctx.createLinearGradient(0, 0, 0, 450);
        gradient.addColorStop(0, 'rgba(113, 126, 195, 0.78)');
        gradient.addColorStop(0.4, 'rgba(169, 177, 219, 0)');
        gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');

        var months = {{ $months }};
        var monthlyAmount = {{ $monthlyAmount }}
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    borderColor: gradientStroke,
                    pointBorderColor: gradientStroke,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    borderWidth: 1,
                    backgroundColor: gradient,
                    pointBorderColor: "#fff",
                    fill: true,

                    label: 'Amounts ',
                    data: monthlyAmount,

                }]
            },
            options: {
                elements: {
                    point: {
                        display: true,
                        radius: 1
                    }
                },
                scales: {

                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: true

                        },
                        ticks: {
                            display: true
                        },
                        gridLines: {
                            display: true,
                            drawBorder: true,
                        }

                    },
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: true
                        },

                        ticks: {
                            display: true
                        }

                    },

                },


                plugins: {
                    legend: {
                        display: true,
                        drawBorder: false
                    }
                },

            }
        });
    </script>
@endpush
