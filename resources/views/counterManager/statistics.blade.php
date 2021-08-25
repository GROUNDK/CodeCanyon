@extends('counterManager.layouts.app')

@section('panel')

<div class="row">
    <div class="col-lg-12">
        <h4 class="mb-2 text-left">@lang('Sale Amount')</h4>
        <div class="row mb-15">
            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--success b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--success">
                        <i class="las las la-calendar-day"></i>
                    </div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Today\'s')</p>
                        <h2 class="text--success font-weight-bold">{{ @$owner->general_settings->currency_symbol }}{{ $daily_sale->total_sales??0 }}</h2>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--primary b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--primary"><i class="las la-calendar"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('This Month')</p>
                        <h2 class="text--primary font-weight-bold">{{ @$owner->general_settings->currency_symbol }}{{ collect(array_values($monthly_sale))->sum() }}</h2>

                    </div>

                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--info b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--info"><i class="las la-calendar-week"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('This Year')</p>
                        <h2 class="text--info font-weight-bold">{{ @$owner->general_settings->currency_symbol }}{{ collect(array_values($yearly_sale))->sum() }}</h2>

                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--warning b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--warning"><i class="las la-calendar-check"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('All Time')</p>
                        <h2 class="text--warning font-weight-bold">{{ @$owner->general_settings->currency_symbol }}{{ $all_sale->total_sales??0 }}</h2>

                    </div>
                </div><!-- widget end -->
            </div>
        </div>
        <h4 class="mb-2 text-left">@lang('Ticket Count')</h4>
        <div class="row mb-15">
            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--green b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--green">
                        <i class="las la-calendar-day"></i>
                    </div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('Today\'s')</p>
                        <h2 class="text--green font-weight-bold">{{ $daily_sale->total_ticket??0 }}</h2>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--deep-purple b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--deep-purple"><i class="las la-calendar"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('This Month')</p>
                        <h2 class="text--deep-purple font-weight-bold">{{ $monthly_ticket??0 }}</h2>
                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--light-blue b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--light-blue"><i class="las la-calendar-week"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('This Year')</p>
                        <h2 class="text--light-blue font-weight-bold">{{ $yearly_ticket??0 }}</h2>

                    </div>
                </div><!-- widget end -->
            </div>

            <div class="col-xl-3 col-md-6 mb-30">
                <div class="widget bb--3 border--amber b-radius--10 bg--white p-4 box--shadow2 has--link">
                    <div class="widget__icon b-radius--rounded bg--amber"><i class="las la-calendar-check"></i></div>
                    <div class="widget__content">
                        <p class="text-uppercase text-muted">@lang('All Time')</p>
                        <h2 class="text--amber font-weight-bold">{{ $all_sale->total_ticket??0 }}</h2>

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
          <h5 class="card-title"> @lang("Sales Report For ". date('F'))</h5>
          <div id="apex-line"> </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 mb-30">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@lang('Sales Report For This Year')</h5>
            <div id="apex-bar-chart"> </div>
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
            height: 420,
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
                data: @json(array_values($monthly_sale)),
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
                categories: @json(array_keys($monthly_sale)),
        },
        yaxis: {
            title: {
                text: "Amount in {{ @$owner->general_settings->currency }}",
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

    var chart   = new ApexCharts(document.querySelector("#apex-line"), options);
    chart.render();

    // apex-bar-chart js
    var options = {
        series: [{
        name: 'Sale',
        data: @json(array_values($yearly_sale))
        }],
        chart: {
        type: 'bar',
        height: 420,
        toolbar: {
            show: false
        }
        },
        plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '5%',
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
        categories: @json(array_keys($yearly_sale)),
        },
        yaxis: {
        title: {
            text: "Amount in {{ @$owner->general_settings->currency }}",
            style: {
                        color: '#7c97bb',
                        fontWeight:'400',
                    }
        }
        },
        fill: {
        opacity: 1
        },
        tooltip: {
        y: {
            formatter: function (val) {
            return  "{{ @$owner->general_settings->currency_symbol }}" + val
            }
        }
    }
    };

    var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
    chart.render();

</script>
@endpush
