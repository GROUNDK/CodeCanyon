@extends('co-owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('co-owner.trip.bus.store', $bus->id??0) }}" method="POST">
                        @csrf

                        <div class="form-group ">
                            <label for="trip">@lang('Trip')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="trip" id="trip" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($trips as $trip)
                                <option value="{{$trip->id}}" data-buses="{{$trip->fleetType->buses}}">{{$trip->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="bus_registration_number">@lang('Bus Registraion Number')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="bus_registration_number" id="bus_registration_number" required>
                                <option selected value="">@lang('Select One')</option>

                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="driver">@lang('Driver')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="driver" id="driver" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($drivers as $driver)
                                <option value="{{$driver->id}}" data-name="{{$driver->name}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="supervisor">@lang('Supervisor')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="supervisor" id="supervisor" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($supervisors as $supervisor)
                                <option value="{{$supervisor->id}}" data-name="{{$supervisor->name}}">{{$supervisor->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">
                                @if (isset($bus))
                                    @lang('Save Changes')
                                @else
                                    @lang('Assign Bus')
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        'use strict';
        (function($){
            $(document).on('change','select[name="trip"]', function () {
                var buses   = $('select[name="trip"]').find("option:selected").data('buses');
                var options = `<option selected value="">@lang('Select One')</option>`

                $.each(buses, function (i, v) {
                    options += `<option value="${v.id}" data-name="${v.registration_no}"> ${v.registration_no} (${v.nick_name}) </option>`
                });

                $('select[name=bus_registration_number]').html(options);

            });

            @if(request()->routeIs('co-owner.trip.bus.edit'))
                $('select[name=trip]').val('{{ $bus->trip_id }}');
                $('select[name=driver]').val('{{ $bus->driver_id }}');

                var buses   = $('select[name="trip"]').find("option:selected").data('buses');
                var options = `<option selected value="">@lang('Select One')</option>`

                $.each(buses, function (i, v) {
                    options += `<option value="${v.id}" data-name="${v.registration_no}"> ${v.registration_no} (${v.nick_name}) </option>`
                });
                $('select[name=bus_registration_number]').html(options);
                $('select[name=bus_registration_number]').val('{{ $bus->bus_id }}');
                $('select[name=supervisor]').val('{{ $bus->supervisor_id }}');
                $('.select2-basic').select2({
                    dropdownParent: $('.card-body')
                });
            @endif
        })(jQuery)

    </script>
@endpush
