@extends('counterManager.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    @isset($title)
                    <div class="card-header">
                            @foreach($title as $key=>$val)
                                <small class="mr-3"><strong>{{ $key }}</strong> : {{ $val }}</small>
                            @endforeach
                    </div>
                    @endisset
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Booking Time')</th>
                                    <th>@lang('Date of Journey')</th>
                                    <th>@lang('Ticket ID')</th>
                                    <th>@lang('Trip')</th>
                                    <th>@lang('Ticket Count')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($sold_tickets as $st)
                            @php
                                $sourceDestination  = getStoppageInfo($st->source_destination)->pluck('name');
                                $source         = $sourceDestination[0];
                                $destination    = $sourceDestination[1];
                            @endphp
                            <tr>
                                <td data-label="@lang('Booking Time')"> {{ showDateTime($st->created_at, 'd/M/y h:i A') }} </td>
                                <td data-label="@lang('Date of Journey')"> {{ showDateTime($st->date_of_journey, 'M d, Y') }} </td>
                                <td data-label="@lang('Ticket ID')"> {{ sprintf('%06d', $st->id) }} </td>
                                <td data-label="@lang('Trip')"> {{ __($st->trip->title) }} </td>

                                <td data-label="@lang('Ticket Count')"> <span class="badge badge-pill badge--dark font-weight-normal">{{ __($st->ticket_count) }}</span></td>

                                <td data-lable="@lang('Amount')">{{ $owner->general_settings->currency_symbol }}{{ $st->ticket_count * $st->price }}</td>

                                <td data-label="@lang('Action')">

                                    <a href="javascript:void(0)" class="icon-btn see-details mr-1" data-ticket="{{ $st }}" data-from="{{ __($source) }}" data-to="{{ __($destination) }}" data-toggle="tooltip" data-placement="top" title="@lang('See Details')">
                                        <i class="la la-eye"></i>
                                    </a>

                                    <a href="{{ route('counterManager.sell.ticket.print', $st->id) }}" class="icon-btn btn--info mr-1" data-toggle="tooltip" data-placement="top" title="@lang('Print Ticket')">
                                        <i class="la la-print"></i>
                                    </a>


                                    @if(request()->routeIs('counterManager.soldTickets.cancelled'))
                                        <a href="javascript:void(0)" class="icon-btn btn--success re-booking" data-toggle="tooltip" data-placement="top" title="@lang('Rebook')" data-id="{{ $st->id }}"><i class="la la-reply"></i></a>
                                    @else
                                        <a href="javascript:void(0)" class="icon-btn btn--danger cancel-booking" data-toggle="tooltip" data-placement="top" title="@lang('Cancel Booking')" data-id="{{ $st->id }}"><i class="la la-times"></i></a>
                                    @endif

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
                    {{ $sold_tickets->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>


    <!--Details Modal -->
    <div class="modal fade" id="seeDetails" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ticket Details')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body p-0">
                    <ul class="list-group">
                        <li class="list-group-item rounded-0 d-flex justify-content-between"><span class="trip-title"></span></li>

                        <li class="list-group-item rounded-0 d-flex justify-content-between"> <span class="font-weight-bold">@lang('Date of Journey')</span><span class="date-of-journey"></span></li>

                        <li class="list-group-item rounded-0 d-flex justify-content-between"><span class="font-weight-bold">@lang('Pickup Point')</span><span class="from"></span></li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between"><span class="font-weight-bold">@lang('Dropping Point')</span><span class="to"></span></li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Ticket Count')</span><span class="ticket-count"></span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Total Price')</span><span class="ticket-price"></span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Seat Number')</span><span class="seat-number"></span>
                        </li>

                        <li class="list-group-item rounded-0 d-flex justify-content-center">
                            <span class="font-weight-bold text--cyan">@lang('Passenger Details')</span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Name')</span><span class="passenger-name"></span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Mobile Number')</span><span class="passenger-mobile"></span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Email')</span><span class="passenger-email"></span>
                        </li>
                        <li class="list-group-item rounded-0 d-flex justify-content-between border-0">
                            <span class="font-weight-bold">@lang('Gender')</span><span class="passenger-gender"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

     <!--Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text--black">@lang('Filter Seales by Entity')</h5></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="{{ route('counterManager.soldTickets.filter') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h6 class="mb-3 text--danger">
                            <i class="la la-info-circle" aria-hidden="true"></i>
                            @lang('Select at least 1 or more fileds as you want and keep rest of the fields empty.')
                        </h6>

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

    <!-- Booking Cancelation Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Booking Cancellation Confirmation')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure to cancel to this booking?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <form action="{{ route('counterManager.soldTickets.cancel') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Re Booking Modal -->
    <div class="modal fade" id="rebookModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Rebooking Confirmation')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure to rebook to this?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <form action="{{ route('counterManager.soldTickets.rebook') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

    @push('breadcrumb-plugins')
        @if(request()->routeIs('owner.report.sale.filtered'))
        <a href="{{ route('counterManger.soldTickets.all') }}" class="btn btn--dark btn--shadow mr-1 filter-btn"><i class="la la-reply"></i> @lang('Back') </a>
        @else
            <form action="" method="GET" class="form-inline float-sm-right bg--white box--shadow1">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Ticket ID')" value="{{ request()->search ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                        @if(request()->has('search'))
                            <a href="{{ url()->previous() }}" class="btn btn--dark rounded-right ">
                                @lang('Clear')
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            @if(!request()->routeIs('counterManager.soldTickets.todays'))
            <button type="button" class="btn btn--success btn--shadow mr-1 filter-btn" data-toggle="modal" data-target="#filterModal"> <i class="fa fa-filter"></i> @lang('Filter') </button>
            @endif
        @endif
    @endpush

@push('script-lib')
    <script src="{{ asset('assets/all_vendors/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/all_vendors/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('.datepicker-here').datepicker({
                maxHours: 24,
            });
            $('.datepicker').css('z-index', 10000);

            $(document).on('click', 'input[name=month]', function(){
                $(document).find('.datepicker--nav').addClass('d-none');
            });

            $(document).on('click', '.see-details', function(){
                var modal   = $('#seeDetails');
                var ticket  = $(this).data('ticket');
                modal.find('.trip-title').text(ticket.trip.title);
                modal.find('.date-of-journey').text(ticket.date_of_journey);
                modal.find('.from').text($(this).data('from'));
                modal.find('.to').text($(this).data('to'));
                modal.find('.ticket-count').text((ticket.ticket_count));
                modal.find('.ticket-price').text((ticket.ticket_count * ticket.price));
                modal.find('.seat-number').text(ticket.seats);
                modal.find('.passenger-name').text((ticket.passenger_details.name));
                modal.find('.passenger-mobile').text((ticket.passenger_details.mobile_number));
                modal.find('.passenger-email').text((ticket.passenger_details.email));
                modal.find('.passenger-gender').text((ticket.passenger_details.gender==1?"{{ trans('Male') }}":ticket.passenger_details.gender==2?"{{ trans('Female') }}":"{{ trans('Others') }}"));
                modal.modal('show');
            });

            $('#filterModal').on('hidden.bs.modal', function (e) {
                $(this).find("input,textarea,select").val('').end()
                $('.select2-basic').select2({
                    dropdownParent: $('.modal')
                });
            })
            $(document).on('click', '.cancel-booking', function(){
                var modal = $('#cancelModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $(document).on('click', '.re-booking', function(){
                var modal = $('#rebookModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);


    </script>
@endpush
