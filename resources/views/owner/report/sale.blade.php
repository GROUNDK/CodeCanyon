@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    @isset($title)
                    <div class="card-header">
                            @foreach($title as $key=>$val)
                                <small class="mr-3"><strong>{{ $key }}</strong> : {{ $val }}</small>
                            @endforeach
                    </div>
                    @endisset
                    <div class="table-responsive--lg table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Booking Time')</th>
                                    <th>@lang('Date of Journey')</th>
                                    <th>@lang('Ticket ID')</th>
                                    <th>@lang('Booked By')</th>
                                    <th>@lang('Trip')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('View')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td data-label="@lang('Booking Time')">{{ showDateTime($sale->created_at, 'M d, Y h:i A') }}</td>
                                    <td data-label="@lang('Date of Journey')">{{ showDateTime($sale->date_of_journey, 'M d, Y') }}</td>
                                    <td data-label="@lang('Ticket ID')" class="font-weight-bold"> {{ sprintf('%06d', $sale->id) }}</td>
                                    <td data-label="@lang('Booked By')"> {{ $sale->counterManager->name }} </td>
                                    <td data-label="@lang('Trip')" class="text-left"> {{ $sale->trip->title }} </td>
                                    <td data-label="@lang('Amount')" class="font-weight-bold"> {{ $owner->general_settings->currency_symbol }}{{  getAmount($sale->ticket_count *  $sale->price)  }}</td>

                                    <td data-label="@lang('View')">
                                        <a href="{{ route('owner.report.sale.detail', $sale->id) }}" class="icon-btn btn--primary"><i class="la la-desktop"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>

                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $sales->appends(['search'=>request()->search ?? null])->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text--black">@lang('Select Entities')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="{{ route('owner.report.sale.filter') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h6 class="mb-3 text--danger">
                            <i class="la la-info-circle" aria-hidden="true"></i>
                            @lang('Select at least 1 or more fileds as you want and keep rest of the fields empty.')
                        </h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"><strong>@lang('Booked By')</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-basic" name="booked_by">
                                    <option value="" selected>@lang('Select One')</option>
                                    @foreach ($counterManagers as $counter_manager)
                                        <option value="{{$counter_manager->id}}">{{$counter_manager->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"><strong>@lang('Route')</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-basic" name="route">
                                    <option value="" selected>@lang('Select One')</option>
                                    @foreach ($routes as $route)
                                        <option value="{{$route->id}}">{{$route->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"><strong>@lang('Trip')</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control select2-basic" name="trip">
                                    <option value="" selected>@lang('Select One')</option>
                                    @foreach ($trips as $trip)
                                    <option value="{{$trip->id}}">{{$trip->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"> <strong>@lang('Booking Date')</strong></label>
                            <div class="col-sm-9">
                                <input type="text" name="booking_date" class="datepicker-here form-control" data-language='en' data-timepicker="false" data-date-format="yyyy-mm-dd" data-position='bottom left' placeholder="@lang('Select Time')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"><strong>@lang('Date of Journey')</strong></label>
                            <div class="col-sm-9">
                                <input type="text" name="date_of_journey" class="datepicker-here form-control" data-language='en' data-date-format="yyyy-mm-dd" value="" data-timepicker="false" data-position='bottom left' placeholder="@lang('Select Date')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"> <strong>@lang('Year')</strong></label>
                            <div class="col-sm-9">
                                <input type="text" name="year" class="datepicker-here form-control" data-language='en' data-min-view="years" data-view="years" data-date-format="yyyy" data-position='top left' placeholder="@lang('Select Year')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"> <strong>@lang('Month')</strong></label>
                            <div class="col-sm-9">
                                <input type="text" name="month" class="datepicker-here form-control" data-language='en' data-min-view="months" data-view="months" data-show-other-years="false" data-date-format="MM" data-position='top left' placeholder="@lang('Select Month')" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text--small"> <strong>@lang('Date to Date')</strong></label>
                            <div class="col-sm-9">
                                <input type="text" name="date_to_date" data-range="true" data-multiple-dates-separator=" to " data-language="en" data-date-format="yyyy-mm-dd" class="datepicker-here form-control" data-position='top left' placeholder="@lang('Start Date to End date')" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn--dark" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-sm btn--success">@lang('Filter')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if(request()->routeIs('owner.report.sale.filtered'))
        <a href="{{ route('owner.report.sale') }}" class="btn btn--dark btn--shadow mr-1 filter-btn"><i class="la la-reply"></i> @lang('Back') </a>
    @else
        <form action="" method="GET" class="form-inline float-sm-right bg--white box--shadow1">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Ticket ID')" value="{{ request()->search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    @if(request()->has('search'))
                        <a href="{{ route('owner.report.sale') }}" class="btn btn--dark rounded-right ">
                            @lang('Clear')
                        </a>
                    @endif
                </div>
            </div>
        </form>
        <button type="button" class="btn btn--success btn--shadow mr-1 filter-btn" data-toggle="modal" data-target="#modelId"> <i class="fa fa-filter"></i> @lang('Filter') </button>
    @endif
@endpush

@push('script-lib')
    <script src="{{ asset('assets/all_vendors/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/all_vendors/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('.datepicker-here').datepicker({
                maxHours: 24,
            });

            $('.datepicker').css('z-index', 10000);

            $(document).on('click', 'input[name=month]', function(){
                $(document).find('.datepicker--nav').addClass('d-none');
            });
        })(jQuery)
    </script>
@endpush


