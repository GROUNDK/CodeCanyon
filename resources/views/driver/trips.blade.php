@extends('driver.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Trip')</th>
                                <th>@lang('Supervisor')</th>
                                <th>@lang('Departure')</th>
                                <th>@lang('Arrival')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Day Off')</th>
                                <th>@lang('View')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trips as $item)
                            @php
                                $date   = Carbon\Carbon::parse($item->trip->schedule->starts_from);
                                $now    = Carbon\Carbon::parse($item->trip->schedule->ends_at);
                                $diff   = $date->diff($now);
                            @endphp
                            <tr>
                                <td data-label="@lang('S.N.')">{{$item ->current_page-1 * $item ->per_page + $loop->iteration }}</td>
                                <td data-label="@lang('Trip')">{{ $item->trip->title }}</td>
                                <td data-label="@lang('Supervisor')">{{ $item->supervisor->name }}</td>
                                <td data-label="@lang('Departure')">
                                    {{ showDateTime($item->trip->schedule->starts_from, 'h:i A') }} {{getPackageLimitUnit($item->unit)}}
                                    <span class="d-block">{{ $item->trip->startingPoint->name }}</span>
                                </td>
                                <td data-label="@lang('Arrival')">
                                    {{ showDateTime($item->trip->schedule->ends_at, 'h:i A') }} {{getPackageLimitUnit($item->unit)}}
                                    <span class="d-block">{{ $item->trip->destinationPoint->name }}</span>
                                </td>
                                @if($diff->i > 0)
                                <td data-label="@lang('Duration')">{{ $diff->format('%h Hours %i minutes') }}</td>
                                @else
                                <td data-label="@lang('Duration')">{{ $diff->format('%h Hours') }}</td>
                                @endif

                                <td data-label="@lang('Day Off')">
                                    @if($item->trip->day_off)
                                        @foreach ($item->trip->day_off as $dayoff)
                                            <span class="text--danger">{{ showDayOff($dayoff) }}</span>
                                        @endforeach
                                    @else
                                        @lang('No Off Day')
                                    @endif
                                </td>

                                <td ata-label="@lang('View')">
                                    <a href="{{ route('driver.trips.view', [$item->id, slug($item->trip->title)]) }}" class="icon-btn"><i class="la la-desktop"></i></a>
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
                {{ $trips->links('admin.partials.paginate') }}
            </div>
        </div><!-- card end -->
    </div>
</div>

</div>
@endsection


