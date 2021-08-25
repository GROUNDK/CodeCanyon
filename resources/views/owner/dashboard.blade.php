@extends('owner.layouts.app')

@section('panel')

<div class="row">
    <div class="col-lg-12">

        @if(count($active_packages) == 0)
        <div class="alert border border--danger bg--white" role="alert">
            <div class="alert__icon bg--danger"><i class="far fa-bell"></i></div>
            <p class="alert__message"> @lang('You\'ve no active package. Please buy a package from')
                <a href="{{ route('owner.package.index') }}">@lang('here')...</a>
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
        @endif

        <div class="row mb-none-30">
            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--success b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.fleet_manage.bus') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--success">
                        <i class="las la-bus"></i>
                    </div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Bus')</p>
                        <h2 class="text--success font-weight-bold">{{ $total_bus }}</h2>

                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--primary b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.driver.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--primary"><i class="las la-user-astronaut"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Drivers')</p>
                        <h2 class="text--primary font-weight-bold">{{ $total_driver }}</h2>

                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--info b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.supervisor.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--info"><i class="las la-user-tie"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Supervisors')</p>
                        <h2 class="text--info font-weight-bold">{{ $total_supervisor }}</h2>

                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--indigo b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.co-owner.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--indigo"><i class="las la-users"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Co-Admin')</p>
                        <h2 class="text--indigo font-weight-bold">{{ $total_coAdmin }}</h2>
                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>



            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--primary b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.trip.route') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--primary"><i class="las la-route"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Routes')</p>
                        <h2 class="text--primary font-weight-bold">{{ $total_route }}</h2>
                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-4 col-md-6 mb-30">
                <div class="widget bb--3 border--success b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.trip.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--success"><i class="las la-radiation-alt"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Trips')</p>
                        <h2 class="text--success font-weight-bold">{{ $total_trip }}</h2>

                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-6 col-md-6 mb-30">
                <div class="widget bb--3 border--indigo b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.counter.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--indigo"><i class="las la-landmark"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Total Counters')</p>
                        <h2 class="text--indigo font-weight-bold">{{ $total_counter }}</h2>
                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-6 col-md-6 mb-30">
                <div class="widget bb--3 border--info b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <a href="{{ route('owner.counter_manager.index') }}" class="item--link"></a>
                    <div class="widget__icon b-radius--rounded bg--info"><i class="las la-user-alt"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Counter Managers')</p>
                        <h2 class="text--info font-weight-bold">{{ $total_cManager }}</h2>

                    </div>
                    <div class="widget__arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div><!-- widget end -->
            </div>

        </div>
    </div>
</div>


<div class="row mb-none-30 mt-4">
    <div class="col-xl-6 mb-30">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"> @lang('Sales Report For') {{ date('F') }}</h5>
          <div id="apex-line"> </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 mb-30">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@lang('Total Sale By Route')</h5>
            <div id="apex-circle-chart"> </div>
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
    // apex-line chart
    var options = {
        chart: {
            height: 316,
            type: "area",
            toolbar: {
            show: false
            },
            dropShadow: {
            enabled: true,
            enabledSeries: [0],
            top: -2,
            left: 0,
            blur: 10,
            opacity: 0.08
            },
            animations: {
            enabled: true,
            easing: 'linear',
            dynamicAnimation: {
                speed: 1000
            }
            },
        },
        dataLabels: {
            enabled: false,
            formatter: function(val, opt) {
            return  `{{ @$owner->general_settings->currency_symbol }}${val}`
            },
            offsetX: 0,
        },
        markers: {
            colors: ['#F44336', '#E91E63', '#9C27B0']
        },
        series: [
            {
                name: "Total Sale",
                data: @json($monthly_sale['amount']),
            },

        ],
        tooltip: {
            y:{
                formatter: function(val, opt) {
                return `{{ @$owner->general_settings->currency_symbol }}${val}`
                },
            }
        },

        offsetX: 0,
        fill: {
            type: "gradient",
            gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 90, 100]
            },
            colors: ['#1E9FF2', '#101536', '#7367F0']
        },
        colors: ['#101536'],
        xaxis: {
                name: 'Date',
                categories: @json($monthly_sale['date']),
        },
        yaxis: {
            title: {
                text: "Amount in {{@$owner->general_settings->currency}}",
                style: {
                    color: '#7c97bb',
                    fontWeight:'400',
                }
            }
        },
        grid: {
            padding: {
            left: 5,
            right: 5
            },
            xaxis: {
                type: 'datetime',
                lines: {
                    show: false
                }
            },
            yaxis: {
            lines: {
                show: false
            }
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#apex-line"), options);
    chart.render();

    // apex-circle-chart js
    var options = {
        series: @json($booked_ticket['sale_price']->flatten()),
        chart: {
            height: 330,
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                offsetY: 0,
                startAngle: 0,
                endAngle: 270,
                hollow: {
                margin: 5,
                size: '30%',
                background: 'transparent',
                image: undefined,
                },
                dataLabels: {
                name: {
                    show: true,
                },
                value: {
                    show: true,
                    formatter: function (val) {
                    return `{{ @$owner->general_settings->currency_symbol }}${val}`
                    }
                }
                }
            }
        },
        labels: @json($booked_ticket['route_name']->flatten()),
            legend: {
            show: true,
            floating: true,
            fontSize: '16px',
            position: 'left',
            offsetX: -25,
            offsetY: -10,
            labels: {
                useSeriesColors: true,
            },
            markers: {
                size: 0
            },
            formatter: function(seriesName, opts) {
                return seriesName + ":  {{ @$owner->general_settings->currency_symbol }}" + opts.w.globals.series[opts.seriesIndex]
            },
            itemMargin: {
                vertical: 3
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    show: false
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#apex-circle-chart"), options);
    chart.render();

</script>
@endpush
