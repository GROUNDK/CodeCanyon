@extends('counterManager.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-xl-5 col-lg-12">
            <div class="card">
            <div class="card-body">
                <form action="{{ route('counterManager.sell.book.booked', $trip->id) }}" class="mt-2" id="booking-form" method="POST">
                    @csrf
                    <h5 class=" text-center">@lang('Passenger Details')</h5>
                    <div class="form-group">
                        <label for="date">@lang('Date of Journey')</label>
                        <input type="text" name="date_of_journey" class="datepicker-here form-control" data-language='en' data-date-format="yyyy-mm-dd" data-position='bottom left' value="{{ date('Y-m-d') }}" placeholder="@lang('Select Date')" autocomplete="off">

                        <small class="text--small text--danger"> <i class="fa fa-info-circle" aria-hidden="true"></i> @lang('Year-Month-Date')</small>
                    </div>

                    <div class="form-group">
                        <label for="pick_up_point">@lang('Pickup Point')<span class="text-danger">*</span></label>
                        <select class="custom-select select2-basic" name="pick_up_point" id="pick_up_point" required>
                            <option selected value="">@lang('Select One')</option>
                            @foreach($stoppages as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dropping_point">@lang('Dropping Point')<span class="text-danger">*</span></label>
                        <select class="custom-select select2-basic" name="dropping_point" id="dropping_point" required>
                            <option selected value="">@lang('Select One')</option>
                            @foreach($stoppages as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="booked-seat-details my-3 d-none">
                        <label>@lang('Selected Seats')</label>
                        <ul class="list-group seat-details-animate">
                            <li class="list-group-item bg--primary">@lang('Seat Details')</li>
                        </ul>
                    </div>

                    <input type="hidden" name="price"/>

                    <div class="form-group">
                        <label for="name">@lang('Name')<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="@lang('Type Here...')"  required/>
                    </div>

                    <div class="form-group">
                        <label for="mobile_number">@lang('Mobile Number')<span class="text-danger">*</span></label>
                        <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="@lang('Type Here...')"  required/>
                    </div>

                    <div class="form-group">
                        <label for="email">@lang('Email')</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="@lang('Type Here...')">
                    </div>

                    <input type="hidden" name="seat_number"/>

                    <label class="d-block">@lang('Gender')<span class="text-danger">*</span></label>
                    <div class="radio-box-wrapper d-flex flex-wrap">
                        <div class="form-radio-box mr-3">
                            <input type="radio" id="gender_2" value="1" name="gender">
                            <label for="gender_2">@lang('Male')</label>
                        </div>
                        <div class="form-radio-box mr-3">
                            <input type="radio" id="gender_1" value="2" name="gender">
                            <label for="gender_1">@lang('Female')</label>
                        </div>
                        <div class="form-radio-box">
                            <input type="radio" id="gender_0" value="0" name="gender">
                            <label for="gender_0">@lang('Others')</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block btn--primary">@lang('Book')</button>
                </form>
            </div>
            </div>
        </div>
        <div class="col-xl-7 col-lg-12 text-center">
            <div class="card">
                <div class="card-body">

                    <h5 class="p-2 text-center mb-2 border-bottom">@lang('Select Seats')</h5>

                    <div class="seat-plan-info">
                        <div class="seat-plan-info-single">
                            <span class="color-box available"></span>
                            <span class="caption">@lang('Available')</span>
                        </div>

                        <div class="seat-plan-info-single">
                            <span class="color-box selected"></span>
                            <span class="caption">@lang('Selacted')</span>
                        </div>

                        <div class="seat-plan-info-single">
                            <span class="color-box booked"></span>
                            <span class="caption"> @lang('Booked') : @lang('Male')</span>
                        </div>

                        <div class="seat-plan-info-single">
                            <span class="color-box booked-female"></span>
                            <span class="caption"> @lang('Booked') : @lang('Female')</span>
                        </div>

                        <div class="seat-plan-info-single">
                            <span class="color-box booked-others"></span>
                            <span class="caption"> @lang('Booked') : @lang('Others')</span>
                        </div>


                    </div>

                    @foreach($trip->fleetType->seats as $deck=>$seat)
                    <div class="seat-plan-wrapper">
                        @if($deck == 1)
                        <div class="diver-seat text-right">
                            <button type="button" class="seat-btn driver-seat" disabled><i class="la la-radiation-alt"></i></button>
                        </div>
                        @else
                        <div class="diver-seat text-right">
                            <h5 class="mb-0">@lang('Deck'): {{ $deck }}</h5>
                        </div>
                        @endif
                        <div class="seat-plan">
                            @php
                                $seatLayout = seatLayoutToArray($trip->fleetType->seat_layout);
                                $left       = $seatLayout[0];
                                $right      = $seatLayout[1];
                                $rowItem    = $left + $right;

                                $totalRow   = floor ($seat / $rowItem );

                                if($seat/$rowItem >0 && $seat/$rowItem <1){
                                    $totalRow = 1;
                                    $seatCount =  $seat;
                                }

                                $lastRowSeat= $seat -  $totalRow * $rowItem;
                                $chr        = 'A';
                            @endphp
                            @for($i = 1; $i <= $totalRow; $i++)
                                @php
                                    $seatNumber = $chr;
                                    $chr++;
                                @endphp

                                <div class="single-row">
                                    <div class="left">
                                        @for($l = 1; $l <= $left; $l++)
                                            <button type="button" class="seat-btn" value="{{ $deck }} - {{ $seatNumber }}{{ $l }}">{{ $seatNumber }}{{ $l }}</i></button>
                                        @endfor
                                    </div>

                                    <div class="right">
                                        @for($r = 0; $r < $right; $r++)
                                            <button type="button" class="seat-btn" value="{{ $deck }} - {{ $seatNumber }}{{ $l + $r }}">{{ $seatNumber }}{{ $l + $r }}</i></button>
                                        @endfor
                                    </div>
                                </div>
                            @endfor

                            @if($lastRowSeat == 1)
                            @php @$seatNumber++ @endphp
                                <div class="single-row d-flex">
                                    <button type="button" value="{{ $deck }} - {{ $seatNumber }}1" class="seat-btn">{{ $seatNumber }}1</i></button>
                                </div>
                            @endif

                            @if($lastRowSeat > 1)
                                @php $seatNumber++ @endphp
                                <div class="single-row">
                                    @for($l = 1; $l <= $lastRowSeat; $l++)
                                        <button type="button" class="seat-btn" value="{{ $deck }} - {{ $seatNumber }}{{ $l }}">{{ $seatNumber }}{{ $l }}</i></button>
                                    @endfor
                                </div><!-- single-row end -->
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg--primary">
                    <h5 class="modal-title text-white">@lang('Ticket Prices: Stoppage to Stoppage')</h5>
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <ul class="list-group">

                        @foreach ($ticket_prices as $item)
                        @if($item->price > 0)
                        @php
                        $stoppages = getStoppageInfo($item->source_destination);

                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stoppages[0]->name }} - {{ $stoppages[1]->name }}
                            <span class="font-weight-bolder">{{ $item->price }}{{ $owner->general_settings->currency_symbol }} </span>
                        </li>
                        @endif
                        @endforeach

                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="close ml-auto m-3" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body text-center">
                    <i class="las la-times-circle f-size--100 text--danger mb-15"></i>

                    <h5 class="text--danger mb-15 error-message"></h5>

                    <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Continue')</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="bookConfirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirm Booking')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('Are you sure to book these seats?')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                <button type="button" class="btn btn--success" id="confirm-book">@lang('Yes')</button>
            </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('How to book a ticket?')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body p-0">
                    <h4 class="text-danger mb-3 px-3 pt-2 text-center">@lang('Check off days before booking')</h4>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 1') <span> @lang('Select Date of Journey')</span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 2') <span> @lang('Select Pickup Point')</span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 3') <span> @lang('Select Dropping Point')</span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 4') <span> @lang('Select Select One or More Seats')</span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 5') <span> @lang('Fill Up Passenger\'s Details')</span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Step 6') <span> @lang('Click/Tap on Book Button') </span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0 border-0">@lang('Step 7') <span> <i class="la la-print"></i> @lang('Ticket')</span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>


@endsection



@push('breadcrumb-plugins')
<button type="button" class="btn btn-sm btn--primary" data-toggle="modal" data-target="#modelId">
    @lang('Ticket Price List')
</button>

 <!-- Button trigger modal -->
 <button type="button" class="btn btn--success btn-sm" data-toggle="modal" data-target="#helpModal">
    @lang('Help') <i class="fa fa-question-circle"></i>
  </button>

@endpush

@push('script')
    <script>
        'use strict';
        (function ($) {
            var booked_seats = JSON.parse('@php echo json_encode($booked_tickets) @endphp');

            console.log(booked_seats);

            $.each(booked_seats, function (i, v) {
                $.each(v.seats, function (index, val) {
                    var title = `${v.passenger_details.from} To ${v.passenger_details.to}`;
                    if(v.passenger_details.gender == 1)
                    $(`.seat-btn[value="${val}"]`).addClass('booked').attr('disabled', 'disabled').attr('title', `${title}`);
                    else if (v.passenger_details.gender == 2)
                    $(`.seat-btn[value="${val}"]`).addClass('booked-female').attr('disabled', 'disabled').attr('title', `${title}`);
                    else if (v.passenger_details.gender == 0)
                    $(`.seat-btn[value="${val}"]`).addClass('booked-others').attr('disabled', 'disabled').attr('title', `${title}`);
                });
            });



            $(document).on('click', '.seat-btn', function () {
                var pick_up_point   = $('select[name="pick_up_point"]').val();
                var dropping_point   = $('select[name="dropping_point"]').val();

                if(pick_up_point && dropping_point){
                    $(this).toggleClass('selected');
                    var seats       = $('.selected').map((_,el) => el.value).get();
                    var price       = $('input[name=price]').val();
                    var seat_data   = `
                                    <li class="list-group-item d-flex justify-content-between font-weight-bolder"> Deck - Seat <span>@lang('Price')</span></li>`;
                    if(seats.length > 0){
                        $('.booked-seat-details').removeClass('d-none');
                        $.each(seats, function (i, v) {
                            seat_data += `<li class="list-group-item d-flex justify-content-between"> ${v} <span class="text--indigo">${price}</span></li>`;
                        });
                        seat_data += `<li class="list-group-item d-flex justify-content-between font-weight-bold"> Subtotal <span>${seats.length * price}</span></li>`;
                        $('.booked-seat-details .list-group').html(seat_data);
                    }else{
                        $('.booked-seat-details .list-group').html('');
                        $('.booked-seat-details').addClass('d-none');
                    }
                    $('input[name=seat_number]').val(seats);
                }else{
                    var modal = $('#alertModal');
                    modal.find('.error-message').text("{{ trans('Please select pickup point and dropping point before select any seat') }}");
                    modal.modal('show');
                }
            });

            $(document).on('change', 'select[name="pick_up_point"], select[name="dropping_point"], input[name="date_of_journey"]', function (e) {
                var date            = $('input[name="date_of_journey"]').val();

                var sourceId        = $('select[name="pick_up_point"]').find("option:selected").val();
                var destinationId   = $('select[name="dropping_point"]').find("option:selected").val();


                if(sourceId == destinationId && destinationId != '' ){
                    var modal = $('#alertModal');
                    modal.find('.error-message').text("{{ trans('Source Point and Destination Point Must Not Be Same') }}");
                    modal.modal('show');
                    return false;
                }else if( sourceId != destinationId){

                    var routeId         = '{{ $trip->route->id }}';
                    var fleetTypeId     = '{{ $trip->fleetType->id }}';

                    if(sourceId && destinationId ){
                        getprice(routeId, fleetTypeId, sourceId, destinationId, date)
                    }
                }
            });

            $('.datepicker-here').datepicker({
                onSelect: function(dateText) {
                    var date = $('.datepicker-here').val();
                    $.ajax({
                        type: "get",
                        url: "{{ route('counterManager.sell.book.bydate', ['ticket_prices_id' => $ticket_price_id, 'id'=>$trip->id]) }}",
                        data: {
                            "date":date
                        },

                        success: function (response) {

                            $(`.seat-btn`).removeClass('booked');
                            $(`.seat-btn`).removeAttr('disabled');
                            $(`.seat-btn`).first().attr('disabled', 'disabled')

                            fillBookedSeats(response);
                        }
                    });

                }
            }).data('datepicker').selectDate(
                new Date($('.datepicker-here').val())
            );

            function fillBookedSeats(response){
                console.log();

                $.each(response.booked_seats, function (i, v) {
                    $.each(v.seats, function (index, val) {

                        var title = `${v.passenger_details.from} To ${v.passenger_details.to}`;
                        if(v.passenger_details.gender == 1)
                        $(`.seat-btn[value="${val}"]`).addClass('booked').attr('disabled', 'disabled').attr('title', `${title}`);
                        else if (v.passenger_details.gender == 2)
                        $(`.seat-btn[value="${val}"]`).addClass('booked-female').attr('disabled', 'disabled').attr('title', `${title}`);
                        else if (v.passenger_details.gender == 0)
                        $(`.seat-btn[value="${val}"]`).addClass('booked-others').attr('disabled', 'disabled').attr('title', `${title}`);
                    });
                });
            }



            $('#alertModal').on('hidden.bs.modal', event => {
                $('select[name="dropping_point"]').val('');
                $('.select2-basic').select2({
                    dropdownParent: $('.card-body form')
                });
            });

            $(document).on('submit', '#booking-form', function(e){
                var modal = $('#bookConfirm');
                e.preventDefault();
                modal.modal('show');
            });

            $(document).on('click', '#confirm-book', function(e){
                var modal = $('#bookConfirm');
                modal.modal('hide');
                document.getElementById("booking-form").submit();
            });



            function getprice(routeId, fleetTypeId, sourceId, destinationId, date) {

                var data = {
                    "trip_id"       : '{{ $trip->id }}',
                    "route_id"      :  routeId,
                    "fleet_type_id" :  fleetTypeId,
                    "source_id"     :  sourceId,
                    "destination_id":  destinationId,
                    "date"          :  date,
                }

                $.ajax({
                    type: "get",
                    url: "{{ route('counterManager.ticket.get-price') }}",
                    data: data,
                    success: function (response) {

                        if(response.error){
                            var modal = $('#alertModal');
                            modal.find('.error-message').text(response.error);
                            modal.modal('show');
                        }else{
                            var stoppages       = response.stoppages;

                            var req_source      = response.req_source;
                            var req_destination = response.req_destination;

                            req_source          = stoppages.indexOf(req_source.toString());
                            req_destination     = stoppages.indexOf(req_destination.toString());

                            var title = ``;

                            if(response.reverse == true){
                                $.each(response.bookedSeats, function (i, v) {
                                    var booked_source       = v.pick_up_point; //Booked
                                    var booked_destination  = v.dropping_point; //Booked

                                    booked_source           = stoppages.indexOf(booked_source.toString());
                                    booked_destination      = stoppages.indexOf(booked_destination.toString());

                                    if( req_destination >= booked_source || req_source <= booked_destination) {
                                        $.each(v.seats, function (index, val) {
                                            if(v.passenger_details.gender == 1)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked').removeAttr('disabled').removeAttr('title');
                                            if(v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked-female').removeAttr('disabled').removeAttr('title');
                                            if(v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked-others').removeAttr('disabled').removeAttr('title');
                                        });
                                    }else{
                                        $.each(v.seats, function (index, val) {
                                            title = `${v.passenger_details.from} to ${v.passenger_details.to}`;

                                            if(v.passenger_details.gender == 1)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked').attr('disabled', 'disabled').attr('title', `${title}`);
                                            else if (v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked-female').attr('disabled', 'disabled').attr('title', `${title}`);
                                            else if (v.passenger_details.gender == 0)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked-others').attr('disabled', 'disabled').attr('title', `${title}`);
                                        });
                                    }
                                });
                            }else{
                                $.each(response.bookedSeats, function (i, v) {
                                    var booked_source       = v.pick_up_point; //Booked
                                    var booked_destination  = v.dropping_point; //Booked

                                    booked_source           = stoppages.indexOf(booked_source.toString());
                                    booked_destination      = stoppages.indexOf(booked_destination.toString());


                                    if( req_destination <= booked_source || req_source >= booked_destination) {
                                        $.each(v.seats, function (index, val) {
                                            if(v.passenger_details.gender == 1)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked').removeAttr('disabled').removeAttr('title');
                                            if(v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked-female').removeAttr('disabled').removeAttr('title');
                                            if(v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).removeClass('booked-others').removeAttr('disabled').removeAttr('title');
                                        });
                                    }else{
                                        $.each(v.seats, function (index, val) {
                                            var title = `${v.passenger_details.from} to ${v.passenger_details.to}`;

                                            if(v.passenger_details.gender == 1)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked').attr('disabled', 'disabled').attr('title', `${title}`);
                                            else if (v.passenger_details.gender == 2)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked-female').attr('disabled', 'disabled').attr('title', `${title}`);
                                            else if (v.passenger_details.gender == 0)
                                            $(`.seat-btn[value="${val}"]`).addClass('booked-others').attr('disabled', 'disabled').attr('title', `${title}`);
                                        });
                                    }
                                });
                            }

                            if(response.price.error){
                                var modal = $('#alertModal');
                                modal.find('.error-message').text(response.price.error);
                                modal.modal('show');
                            }else{
                                $('input[name=price]').val(response.price);
                            }
                        }
                    }
                });
            }
        })(jQuery);
    </script>
@endpush

@push('script-lib')
<script type="text/javascript" src="{{ asset('assets/all_vendors/js/vendor/datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/all_vendors/js/vendor/datepicker.en.js') }}"></script>
@endpush
