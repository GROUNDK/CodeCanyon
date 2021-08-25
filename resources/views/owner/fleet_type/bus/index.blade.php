@extends('owner.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive--md  table-responsive">
                    <table class="default-data-table table table-striped">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Brand Name')</th>
                                <th>@lang('Model No.')</th>
                                <th>@lang('Registration No.')</th>
                                <th>@lang('Fleet Type')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buses as $bus)
                                <tr>
                                    <td data-label="@lang('S.N.')">
                                        {{ $bus ->current_page-1 * $bus ->per_page + $loop->iteration }}
                                    </td>

                                    <td data-label="@lang('Name')">{{ $bus->nick_name }}</td>

                                    <td data-label="@lang('Brand Name')">{{ $bus->brand_name }}</td>

                                    <td data-label="@lang('Model No.')">{{ $bus->model_no }}</td>
                                    <td data-label="@lang('Registration No.')">{{ $bus->registration_no }}</td>

                                    <td data-label="@lang('Fleet Type')">{{ $bus->fleetType->name}} - {{ $bus->fleetType->total_seat }} ({{ $bus->fleetType->has_ac?'AC':'Non AC' }})</td>

                                    <td data-label="@lang('Status')">
                                        <span
                                            class="text--small badge font-weight-normal badge--{{ $bus->status?'success':'danger' }}">
                                            {{ $bus->status?trans('Active'):trans('Inactive') }}
                                        </span>
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" data-bus="{{ $bus }}" class="icon-btn edit-btn"
                                            data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                            <i class="la la-pencil"></i>
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

        </div><!-- card end -->
    </div>
</div>

<div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add New Bus')
                </h5>
                <div class="text-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip"
                        title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <a href="{{ route('owner.fleet_manage.bus.create') }}" class="close"
                        data-toggle="tooltip" title="@lang('Open in New Page')"><span aria-hidden="true">&#10064;</span></a>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ route('owner.fleet_manage.bus.store', 0) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">@lang('Nick Name')<span class="text-danger">*</span></label>
                        <input type="text" name="nick_name" id="name" class="form-control" placeholder="@lang('Type Here...')" required/>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="registration_no">@lang('Registration No.')<span class="text-danger">*</span></label>
                                <input type="text" name="registration_no" id="registration_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="engine_no">@lang('Engine No.')<span class="text-danger">*</span></label>
                                <input type="text" name="engine_no" id="engine_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model_no">@lang('Model No.')<span class="text-danger">*</span></label>
                                <input type="text" name="model_no" id="model_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chasis">@lang('Chasis No.')<span class="text-danger">*</span></label>
                                <input type="text" name="chasis_no" id="chasis" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner">@lang('Owner Name')<span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" id="owner" class="form-control" placeholder="@lang('Jane Smith')" required/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner_phone">@lang('Owner Phone') <span class="text-danger">*</span></label>
                                <input type="text" name="owner_phone" id="owner_phone" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand">@lang('Brand Name')<span class="text-danger">*</span></label>
                        <input type="text" name="brand_name" id="brand" class="form-control" placeholder="@lang('Type Here...')" required/>
                    </div>

                    <div class="form-group">
                        <label for="fleet_type">@lang('Fleet Type')<span class="text-danger">*</span></label>
                        <select class="custom-select" name="fleet_type" id="fleet_type" required>
                            <option selected value="">@lang('Select One')</option>
                            @foreach($fleet_types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <input type="checkbox" name="status" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" checked>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn--primary"> @lang('Add Bus') </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Bus')</h5>
                <div class="text-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip"
                        title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <a href="" class="close" data-toggle="tooltip" title="@lang('Open in New Page')">
                        <span aria-hidden="true">&#10064;</span>
                    </a>
                </div>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">@lang('Nick Name')<span class="text-danger">*</span></label>
                        <input type="text" name="nick_name" id="name" class="form-control" placeholder="@lang('Type Here...')" required/>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="registration_no">@lang('Registration No.')<span class="text-danger">*</span></label>
                                <input type="text" name="registration_no" id="registration_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="engine_no">@lang('Engine No.')<span class="text-danger">*</span></label>
                                <input type="text" name="engine_no" id="engine_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model_no">@lang('Model No.')<span class="text-danger">*</span></label>
                                <input type="text" name="model_no" id="model_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chasis">@lang('Chasis No.')<span class="text-danger">*</span></label>
                                <input type="text" name="chasis_no" id="chasis" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner">@lang('Owner Name')<span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" id="owner" class="form-control" placeholder="@lang('Jane Smith')" required/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner_phone">@lang('Owner Phone')<span class="text-danger">*</span></label>
                                <input type="text" name="owner_phone" id="owner_phone" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="brand">@lang('Brand Name')<span class="text-danger">*</span></label>
                        <input type="text" name="brand_name" id="brand" class="form-control" placeholder="@lang('Type Here...')" required/>
                    </div>

                    <div class="form-group">
                        <label for="fleet_type">@lang('Fleet Type')<span class="text-danger">*</span></label>
                        <select class="custom-select" name="fleet_type" id="fleet_type" required>
                            <option selected value="">@lang('Select One')</option>
                            @foreach($fleet_types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <input type="checkbox" name="status" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" checked>
                    </div>


                    <div class="form-group ">
                        <button type="submit" class="btn btn-block btn--primary">@lang('Save Changes')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
                <div class="modal-body"></div>
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

    <button data-toggle="modal" data-target="#addModal" class="btn btn-sm btn--success text--small box--shadow1">
        <i class="las la-plus"></i>@lang('Add New')
    </button>

@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('click', '.edit-btn', function () {
                var modal   = $('#editModal');
                var data    = $(this).data('bus');
                var link    = `{{ route('owner.fleet_manage.bus.store', '') }}/${data.id}`;

                modal.find('input[name=nick_name]').val(data.nick_name);
                modal.find('input[name=registration_no]').val(data.registration_no);
                modal.find('input[name=engine_no]').val(data.engine_no);
                modal.find('input[name=model_no]').val(data.model_no);
                modal.find('input[name=chasis_no]').val(data.chasis_no);
                modal.find('input[name=owner_name]').val(data.owner);
                modal.find('input[name=owner_phone]').val(data.owner_phone);
                modal.find('input[name=brand_name]').val(data.brand_name);
                modal.find('select[name=fleet_type]').val(data.fleet_type_id);

                if(data.status == 0){
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',false);

                }else{
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',true);
                }

                var elink = `{{ route('owner.fleet_manage.bus.edit','') }}/${data.id}`

                modal.find('.close').attr('href', elink);
                modal.find('form').attr('action', link);
                modal.modal('show');
            });

        })(jQuery)

    </script>
@endpush
