@extends('co-owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--lg  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Registration No.')</th>
                                    <th>@lang('Driver')</th>
                                    <th>@lang('Supervisor')</th>
                                    <th>@lang('Trip')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($assigned_buses as $a_bus)
                            <tr>
                                <td data-label="@lang('S.N.')">{{$a_bus ->current_page-1 * $a_bus ->per_page + $loop->iteration }}</td>
                                <td data-label="@lang('Registration No.')"> {{ $a_bus->bus->registration_no }} </td>

                                <td data-label="@lang('Driver')" class="{{ $a_bus->driver->trashed()?'text--danger':'' }}"> {{ $a_bus->driver->name }} </td>
                                <td data-label="@lang('Supervisor')" class="{{ $a_bus->supervisor->trashed()?'text--danger':'' }}"> {{ $a_bus->supervisor->name }} </td>

                                <td data-label="@lang('Trip')"> {{ $a_bus->trip->title }} </td>
                                <td data-label="@lang('Status')">
                                <span class="text--small badge font-weight-normal badge--{{$a_bus->status?'success':'danger'}}">
                                        {{$a_bus->status?trans('Active'):trans('Inactive')}}
                                    </span>
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-trip="{{ $a_bus }}" class="icon-btn {{ $a_bus->trashed()?'disabled':'edit-btn' }}" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $a_bus->id }}" class="ml-1 icon-btn btn--{{$a_bus->trashed()?'success':'danger'}} delete-btn {{ $a_bus->status==0?'disabled':'' }}" data-toggle="tooltip" data-placement="top" data-action_type="{{$a_bus->trashed()?'restore':'delete'}}" title="{{$a_bus->trashed()?trans('Restore'):trans('Delete')}}">
                                        <i class="la la-trash{{$a_bus->trashed()?'-restore':''}}"></i>
                                    </a>
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
                    {{ $assigned_buses->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Bus to Trip')</h5>
                    <div class="text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <a href="{{route('co-owner.trip.bus.create')}}" class="close" data-toggle="tooltip" title="@lang('Open in New Page')">
                            <span aria-hidden="true">&#10064;</span>
                        </a>
                    </div>
                </div>
                <div class="modal-body">

                    <form action="{{ route('co-owner.trip.bus.store', 0) }}" method="POST">
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
                            <button type="submit" class="btn btn-block btn--primary">@lang('Assign Bus')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Assigned Bus')</h5>
                    <div class="text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <a href="" class="close" data-toggle="tooltip" title="@lang('Open in New Page')"><span aria-hidden="true">&#10064;</span></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group ">
                            <label for="edit-trip">@lang('Trip')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="trip" id="edit-trip" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($trips as $trip)
                                <option value="{{$trip->id}}" data-buses="{{$trip->fleetType->buses}}">{{$trip->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="edit_registration_number">@lang('Bus Registraion Number')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="bus_registration_number" id="edit_registration_number" required>
                                <option selected value="">@lang('Select One')</option>

                            </select>
                        </div>


                        <div class="form-group ">
                            <label for="edit-driver">@lang('Driver')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="driver" id="edit-driver" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($drivers as $driver)
                                <option value="{{$driver->id}}" data-name="{{$driver->name}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="edit-supervisor">@lang('Supervisor')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="supervisor" id="edit-supervisor" required>
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
                        <div class="form-group ">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Removal Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
<div class="row d-flex flex-row-reverse p-2">
    <div class="px-1 col-xl-6 col-lg-12 mb-2 mb-xl-0">
        <form action="" method="GET" class="bg-white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Search...')" value="{{ request()->search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    @if(request()->has('search'))
                    <a href="{{ route('co-owner.trip.bus.index') }}" class="btn btn--dark rounded-right ">
                        @lang('Clear')
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="px-1 col-xl-3 col-lg-12 mb-2 mb-xl-0">
        @if(request()->routeIs('co-owner.trip.bus.index'))
        <button class="btn btn--success mr-1 mb-2 mb-xl-0 btn-block add-btn">
            <i class="las la-plus"></i>@lang('Add New')
        </button>
        @else
            @if(request()->routeIs('co-owner.trip.bus.trashed.search'))
            <a href="{{route('co-owner.trip.bus.trashed')}}" class="btn btn-dark"><i class="la la-reply"></i>@lang('Back')</a>
            @else
            <a href="{{route('co-owner.trip.bus.index')}}" class="btn btn-dark"><i class="la la-reply"></i>@lang('Back')</a>
            @endif
        @endif
    </div>
    <div class="px-1 col-xl-3 col-lg-12 mb-2 mb-xl-0">
        @if(request()->routeIs('co-owner.trip.bus.index'))
        <a href="{{ route('co-owner.trip.bus.trashed') }}" class="btn btn-danger d-block"><i class="fas fa-trash-alt"></i>@lang('Trashed')</a>
        @endif
    </div>

</div>
@endpush

@push('script')
    <script>
        'use strict';

        (function($){
            $(document).on('click', '.add-btn', function () {
                var modal = $('#addModal');
                modal.find('.select2-basic, .select2-multi-select').select2({
                    dropdownParent: modal
                });
                modal.modal('show');
            });

            $(document).on('click', '.edit-btn', function () {
                var modal = $('#editModal');

                var data  = $(this).data('trip');
                var link  = `{{ route('co-owner.trip.bus.store', '') }}/${data.id}`;

                modal.find('select[name=trip]').val(data.trip_id);
                modal.find('select[name=driver]').val(data.driver_id);
                modal.find('select[name=supervisor]').val(data.supervisor_id);



                var buses   = modal.find('select[name=trip]').find("option:selected").data('buses');

                console.log(buses);

                var options = `<option selected value="">@lang('Select One')</option>`

                $.each(buses, function (i, v) {
                    options += `<option value="${v.id}" data-name="${v.registration_no}"> ${v.registration_no} (${v.nick_name}) </option>`
                });

                modal.find('select[name=bus_registration_number]').html(options);

                modal.find('select[name=bus_registration_number]').val(data.bus_id);


                modal.find('.select2-basic').select2({
                    dropdownParent: $('#editModal')
                });

                if(data.status == 0){
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', false);
                }else{
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked', true);
                }

                var elink = `{{route('co-owner.trip.bus.edit','')}}/${data.id}`

                modal.find('.close').attr('href', elink);
                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id  = $(this).data('id');
                var action_type = $(this).data('action_type');
                if(action_type == 'delete'){
                    modal.find('.modal-body').text("{{ trans('Are you sure to delete this Trip?')}}");
                }else{
                    modal.find('.modal-body').text("{{ trans('Are you sure to restore this Trip?')}}");
                }
                var link  = `{{ route('co-owner.trip.bus.remove', '') }}/${id}`;
                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('change','select[name="trip"]', function () {
                var buses   = $(this).parents('.modal-body').find('select[name="trip"]').find("option:selected").data('buses');
                var options = `<option selected value="">@lang('Select One')</option>`

                $.each(buses, function (i, v) {
                    options += `<option value="${v.id}" data-name="${v.registration_no}"> ${v.registration_no} (${v.nick_name}) </option>`
                });

                $(this).parents('.modal-body').find('select[name=bus_registration_number]').html(options);

            });
        })(jQuery)
    </script>
@endpush


