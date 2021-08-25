@extends('driver.layouts.app')

@section('panel')
    <div class="row">

        <div class="col text-center">
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
                                $lastRowSeat= $seat -  $totalRow * $rowItem;
                                $chr        = 'A';
                            @endphp
                            @for($i = 1; $i <= $totalRow; $i++)
                                @php
                                    if($lastRowSeat==1 && $i== $totalRow -1)
                                    break;
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
                                </div><!-- single-row end -->
                            @endfor

                            @if($lastRowSeat == 1)
                            @php $seatNumber++ @endphp
                                <div class="single-row d-flex justify-content-between">
                                    @for ($lsr=1; $lsr <= $rowItem+1; $lsr++)
                                    <button type="button" value="{{ $deck }} - {{ $seatNumber }}{{ $lsr }}" class="seat-btn">{{ $seatNumber }}{{ $lsr }}</i></button>
                                    @endfor
                                </div><!-- single-row end -->
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



@endsection
@push('breadcrumb-plugins')
<a href="{{ route('driver.trips') }}" class="btn btn-sm btn--dark"><i class="la la-reply" aria-hidden="true"></i>@lang('Back')</a>
@endpush

@push('script')
    <script>
        'use strict';
        (function ($) {
            var booked_seats = JSON.parse('@php echo json_encode($trip->bookedTickets) @endphp');
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

        })(jQuery);

    </script>
@endpush

