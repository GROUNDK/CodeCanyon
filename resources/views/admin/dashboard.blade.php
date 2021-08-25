@extends('admin.layouts.app')

@section('panel')
@if(@json_decode($general->sys_version)->version > systemDetails()['version'])
<div class="row">
    <div class="col-md-12">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">
                <h3 class="card-title"> @lang('New Version Available') <button
                        class="btn btn--dark float-right">@lang('Version')
                        {{json_decode($general->sys_version)->version}}</button> </h3>
            </div>
            <div class="card-body">
                <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                <p>
                <pre class="f-size--24">{{json_decode($general->sys_version)->details}}</pre>
                </p>
            </div>
        </div>
    </div>
</div>
@endif
@if(@json_decode($general->sys_version)->message)
<div class="row">
    @foreach(json_decode($general->sys_version)->message as $msg)
    <div class="col-md-12">
        <div class="alert border border--primary" role="alert">
            <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
            <p class="alert__message">@php echo $msg; @endphp</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif
<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--green b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-user-check"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{$widget['verified_users']}}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Active Owners')</span>
                </div>
                <a href="{{route('admin.users.active')}}"
                    class="btn btn-sm bg--white text--black text--small box--shadow3 mt-3">@lang('View All')</a>
            </div>
        </div>
    </div><!-- dashboard-w1 end -->

    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--5 b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-user-times"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{$widget['banned_users']}}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Banned Owners')</span>
                </div>
                <a href="{{route('admin.users.banned')}}"
                    class="btn btn-sm bg--white text--black text--small box--shadow3 mt-3">@lang('View All')</a>
            </div>
        </div>
    </div><!-- dashboard-w1 end -->


    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--3 b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{$widget['active_packages']}}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Active Packages')</span>
                </div>
                <a href="{{route('admin.package.index')}}"
                    class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--17 b-radius--10 box-shadow ">
            <div class="icon">
                <i class="fa fa-dollar-sign"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{$widget['sold_packages']}}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Sale Count')</span>
                </div>

                <a href="{{route('admin.package.sold')}}"
                    class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
            </div>
        </div>
    </div><!-- dashboard-w1 end -->

</div>
<div class="row mt-50 mb-none-30">
    <div class="col-xl-6 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">@lang('Monthly Sales Report')</h5>
                <div id="apex-bar-chart"> </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-30">

        <div class="row mb-none-30">

            <div class="col-lg-6 col-sm-6 mb-30">
                <div class="widget-three box--shadow2 b-radius--5 bg--white">
                    <div class="widget-three__icon b-radius--rounded bg--pink  box--shadow2">
                        <i class="las la-money-bill "></i>
                    </div>
                    <div class="widget-three__content">
                        <h2 class="numbers">{{$general->cur_sym}}{{getAmount($payment['total_deposit_amount'])}}</h2>
                        <p class="text--small">@lang('Total Sale')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
            <div class="col-lg-6 col-sm-6 mb-30">
                <div class="widget-three box--shadow2 b-radius--5 bg--white">
                    <div class="widget-three__icon b-radius--rounded bg--teal box--shadow2">
                        <i class="las la-money-check"></i>
                    </div>
                    <div class="widget-three__content">
                        <h2 class="numbers">{{$general->cur_sym}}{{getAmount(@$payment['total_deposit_charge'])}} </h2>
                        <p class="text--small">@lang('Total Payment Charge')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
            <div class="col-lg-6 col-sm-6 mb-30">
                <div class="widget-three box--shadow2 b-radius--5 bg--white">
                    <div class="widget-three__icon b-radius--rounded bg--green  box--shadow2">
                        <i class="las la-money-bill-wave "></i>
                    </div>
                    <div class="widget-three__content">
                        <h2 class="numbers">{{$payment['total_deposit_pending']}}</h2>
                        <p class="text--small">@lang('Pending Payment')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>

            <div class="col-lg-6 col-sm-6 mb-30">
                <div class="widget-three box--shadow2 b-radius--5 bg--white">
                    <div class="widget-three__icon b-radius--rounded bg--primary box--shadow2">
                        <i class="las la-wallet "></i>
                    </div>
                    <div class="widget-three__content">
                        <h2 class="numbers">{{$payment['payment_method']}}</h2>
                        <p class="text--small">@lang('Total Payment Method')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
        </div>
    </div>
</div><!-- row end -->

<div class="row mb-none-30 mt-5">

    <div class="col-xl-6 mb-30">
        <div class="card ">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('Latest Owners')</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestUser as $user)
                            <tr>
                                <td data-label="@lang('User')">
                                    <div class="user">
                                        <div class="thumb">
                                            <a href="{{ getImage('assets/owner/images/profile/'. $user->image, true)}}"
                                                class="image-popup">
                                                <img src="{{ getImage('assets/owner/images/profile/'. $user->image, true)}}"
                                                    alt="image">
                                            </a>

                                        </div>
                                        <span class="name">{{$user->owner_name}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Username')"><a
                                        href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                                </td>
                                <td data-label="@lang('Email')">{{ $user->email }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="icon-btn"
                                        data-toggle="tooltip" title="" data-original-title="Details">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('No Registerd Owner Yet')</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </div><!-- card end -->
    </div>
    <div class="col-xl-6 mb-30">
        <div class="card ">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('Latest Sales')</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Package')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestSales as $sales)
                            <tr>
                                <td data-label="@lang('User')">
                                    <div class="user">
                                        <div class="thumb">
                                            <a href="{{ getImage('assets/owner/images/profile/'. @$sales->owner->image, true)}}"
                                                class="image-popup">
                                                <img src="{{ getImage('assets/owner/images/profile/'. @$sales->owner->image, true)}}"
                                                    alt="image">
                                            </a>
                                        </div>
                                        <span class="name">{{@$sales->owner->owner_name}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Username')">
                                    <a
                                        href="{{ route('admin.users.detail', @$sales->owner_id) }}">{{ @$sales->owner->username }}</a>
                                </td>
                                <td data-label="@lang('Package')">{{ __($sales->package->name) }}</td>
                                <td data-label="@lang('Price')">{{ $general->cur_sym }}{{ $sales->price }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.users.detail', $sales->id) }}" class="icon-btn"
                                        data-toggle="tooltip" title="" data-original-title="Details">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('No Sell Yet')</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </div><!-- card end -->
    </div>


</div>

<div class="row mb-none-30 mt-5">
    <div class="col-xl-4 col-lg-6 mb-30">
        <div class="card overflow-hidden">
            <div class="card-body">
                <h5 class="card-title">@lang('Login By Browser')</h5>
                <canvas id="userBrowserChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">@lang('Login By OS')</h5>
                <canvas id="userOsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-30">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">@lang('Login By Country')</h5>
                <canvas id="userCountryChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-lib')
<script src="{{asset('assets/all_vendors/js/vendor/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/all_vendors/js/vendor/chart.js.2.8.0.js')}}"></script>
@endpush

@push('script')


<script>
'use strict';
(function($) {
    $('.image-popup').magnificPopup({
        type: 'image'
    });
})(jQuery)
// apex-bar-chart js
var options = {
    series: [{
        name: '{{ trans("Total Payment") }}',
        data: [
            @foreach($report['months'] as $month) {
                {
                    getAmount(@$depositsMonth - > where('months', $month) - > first() - > depositAmount)
                }
            },
            @endforeach
        ]
    }],
    chart: {
        type: 'bar',
        height: 400,
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: @json($report['months'] - > flatten()),
        title: {
            text: "Months",
            style: {
                color: '#7c97bb'
            }
        }
    },
    yaxis: {
        title: {
            text: '{{ trans("Amount in $general->cur_text") }}',
            style: {
                color: '#7c97bb'
            }
        }
    },
    grid: {
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: true
            }
        },
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return "{{$general->cur_sym}}" + val + " ";
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
chart.render();

var ctx = document.getElementById('userBrowserChart');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json($chart['user_browser_counter'] - > keys()),
        datasets: [{
            data: {
                {
                    $chart['user_browser_counter'] - > flatten()
                }
            },
            backgroundColor: [
                '#FF4500',
                '#e52d27',
                '#fba540',
                '#e7505a',
                '#5050bf',
                '#8E44AD',
                '#4f8a8b',
                '#1f4068',
                '#62760c',
                '#be5683',
                '#cf1b1b',
                '#96bb7c',
                '#d3de32',
                '#e8505b',
                '#24a19c',
                '#3b6978',
                '#b83b5e',
                '#ff4301',
                '#c4fb6d',
                '#bac964',
                '#fb7813',
                '#3b6978',
                '#f3c623',
                '#127681',
                '#562349',
                '#1f4068',
                '#035aa6',
                '#95389e',
                '#481380'
            ],
            borderColor: [
                'rgba(231, 80, 90, 0.75)'
            ],
            borderWidth: 0,

        }]
    },
    options: {
        aspectRatio: 1,
        responsive: true,
        maintainAspectRatio: true,
        elements: {
            line: {
                tension: 0 // disables bezier curves
            }
        },
        scales: {
            xAxes: [{
                display: false
            }],
            yAxes: [{
                display: false
            }]
        },
        legend: {
            display: false,
        }
    }
});

var ctx = document.getElementById('userOsChart');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json($chart['user_os_counter'] - > keys()),
        datasets: [{
            data: {
                {
                    $chart['user_os_counter'] - > flatten()
                }
            },
            backgroundColor: [
                '#FF4500',
                '#e52d27',
                '#fba540',
                '#e7505a',
                '#5050bf',
                '#8E44AD',
                '#4f8a8b',
                '#1f4068',
                '#62760c',
                '#be5683',
                '#cf1b1b',
                '#96bb7c',
                '#d3de32',
                '#e8505b',
                '#24a19c',
                '#3b6978',
                '#b83b5e',
                '#ff4301',
                '#c4fb6d',
                '#bac964',
                '#fb7813',
                '#3b6978',
                '#f3c623',
                '#127681',
                '#562349',
                '#1f4068',
                '#035aa6',
                '#95389e',
                '#481380'

            ],
            borderColor: [
                'rgba(0, 0, 0, 0.05)'
            ],
            borderWidth: 0,

        }]
    },
    options: {
        aspectRatio: 1,
        responsive: true,
        elements: {
            line: {
                tension: 0 // disables bezier curves
            }
        },
        scales: {
            xAxes: [{
                display: false
            }],
            yAxes: [{
                display: false
            }]
        },
        legend: {
            display: false,
        }
    },
});

// Donut chart
var ctx = document.getElementById('userCountryChart');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json($chart['user_country_counter'] - > keys()),
        datasets: [{
            data: {
                {
                    $chart['user_country_counter'] - > flatten()
                }
            },
            backgroundColor: [
                '#FF4500',
                '#e52d27',
                '#fba540',
                '#e7505a',
                '#5050bf',
                '#8E44AD',
                '#4f8a8b',
                '#1f4068',
                '#62760c',
                '#be5683',
                '#cf1b1b',
                '#96bb7c',
                '#d3de32',
                '#e8505b',
                '#24a19c',
                '#3b6978',
                '#b83b5e',
                '#ff4301',
                '#c4fb6d',
                '#bac964',
                '#fb7813',
                '#3b6978',
                '#f3c623',
                '#127681',
                '#562349',
                '#1f4068',
                '#035aa6',
                '#95389e',
                '#481380',
            ],
            borderColor: [
                'rgba(231, 80, 90, 0.75)'
            ],
            borderWidth: 3,

        }]
    },
    options: {
        aspectRatio: 1,
        responsive: true,
        elements: {
            line: {
                tension: 0 // disables bezier curves
            }
        },
        scales: {
            xAxes: [{
                display: false
            }],
            yAxes: [{
                display: false
            }]
        },
        legend: {
            display: false,
        }
    }
});
</script>
@endpush
