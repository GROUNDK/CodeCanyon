@extends('counterManager.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            @if(request()->routeIs('counterManager.trip.index'))
                                <thead>
                                    <tr>
                                        <th>@lang('S.N.')</th>
                                        <th>@lang('Title')</th>
                                        <th>@lang('Schedule')</th>
                                        <th>@lang('Day Off')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($trips as $trip)

                                <tr>
                                    <td data-label="@lang('S.N.')">{{$trip->current_page-1 * $trip ->per_page + $loop->iteration }}</td>
                                    <td data-label="@lang('Title')"> {{$trip['title']}} </td>

                                    <td data-label="@lang('Schedule')">{{ showDateTime($trip->schedule->starts_from, 'H:i a') }} @lang('to') {{ showDateTime($trip->schedule->ends_at, 'H:i a') }}</td>

                                    <td data-label="@lang('Day Off')">
                                        @if($trip->day_off)
                                        @foreach ($trip->day_off as $item)
                                            {{ showDayOff($item) }}
                                        @endforeach
                                        @else
                                        @lang('No Off Day')
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">

                                        <a href="{{ route('counterManager.sell.book', ['id'=>$trip->id, 'slug'=> slug($trip->title), 'ticket_prices_id' => $trip->ticket_price_id]) }}" class="icon-btn" data-toggle="tooltip" data-placement="top" title="@lang('Book Ticket')">
                                            <i class="la la-sticky-note"></i>
                                        </a>

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            @else
                                <thead>
                                    <tr>
                                        <th>@lang('Title')</th>
                                        <th>@lang('Departure')</th>
                                        <th>@lang('Arrival')</th>
                                        <th>@lang('Fare')</th>
                                        <th>@lang('Seat Available')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($routes as $route)
                                    @forelse ($route->trips as $trip)
                                        @php
                                            if($route->starting_point == $trip->starting_point && $route->destination_point == $trip->destination_point){
                                                $reverse = false;
                                                $stoppages = $route->stoppages;
                                            }else{
                                                $reverse = true;
                                                $stoppages = array_reverse($route->stoppages);
                                            }

                                            $result = array_intersect($stoppages, $sd_array);
                                            $result = array_values($result);
                                            if($result != $sd_array){
                                                continue;

                                            }

                                            $ticket_price   =  $route->ticketPrice->where('fleet_type_id',$trip->fleet_type_id)->first();
                                            $ticketPrice    = $ticket_price->prices->where('source_destination', $sd_array)->first();
                                            if($ticketPrice == null)
                                            $ticketPrice = $ticket_price->prices->where('source_destination', array_reverse($sd_array))->first();

                                        @endphp

                                        <tr>
                                            <td data-label="@lang('Title')"> {{$trip['title']}} </td>

                                            <td data-label="@lang('Departure')">{{ showDateTime($trip->schedule->starts_from, 'h:i a') }}
                                                <span class="d-block">{{ $trip->startingPoint->name }}</span>
                                            </td>

                                            <td data-label="@lang('Arrival')">
                                                {{ showDateTime($trip->schedule->ends_at, 'h:i a') }}
                                                <span class="d-block">{{ $trip->destinationPoint->name }}</span>
                                            </td>

                                            <td data-label="@lang('Fare')">
                                                {{ $owner->general_settings->currency_symbol }}{{ $ticketPrice->price }}
                                            </td>

                                            <td data-label="@lang('Seat Available')">
                                                {{ collect($trip->fleetType->seats)->sum() - $trip->bookedTickets->sum('ticket_count') }}
                                            </td>

                                            <td data-label="@lang('Action')">
                                                <a href="{{ route('counterManager.sell.book', ['id'=>$trip->id, 'slug'=> slug($trip->title), 'ticket_prices_id' => $ticket_price->id]) }}" class="icon-btn" data-toggle="tooltip" data-placement="top" title="@lang('Book Ticket')">
                                                    <i class="la la-sticky-note"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                        </tr>
                                @endforelse
                                </tbody>
                            @endif
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    @if(request()->routeIs('counterManager.trip.index'))
                    {{ $trips->links('admin.partials.paginate') }}
                    @else
                    {{ $routes->links('admin.partials.paginate') }}
                    @endif
                </div>
            </div><!-- card end -->
        </div>
    </div>

@endsection



@push('breadcrumb-plugins')
    @if(!request()->routeIs('counterManager.trip.index'))
        <form action="" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Route Name / Trip Name')" value="">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif
@endpush



