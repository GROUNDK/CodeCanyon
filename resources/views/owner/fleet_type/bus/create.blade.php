@extends('owner.layouts.app')

@section('panel')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body">
                <form action="{{ route('owner.fleet_manage.bus.store', $bus->id??0) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">@lang('Nick Name')<span class="text-danger">*</span></label>
                                <input type="text" name="nick_name" value="{{$bus->nick_name??null}}" id="name" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="registration_no">@lang('Registration No.')<span class="text-danger">*</span></label>
                                <input type="text" name="registration_no" value="{{$bus->registration_no??null}}" id="registration_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="engine_no">@lang('Engine No.')<span class="text-danger">*</span></label>
                                <input type="text" name="engine_no" value="{{$bus->engine_no??null}}" id="engine_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="model_no">@lang('Model No.')<span class="text-danger">*</span></label>
                                <input type="text" name="model_no" value="{{$bus->model_no??null}}" id="model_no" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="chasis">@lang('Chasis No.')<span class="text-danger">*</span></label>
                                <input type="text" name="chasis_no" value="{{$bus->chasis_no??null}}" id="chasis" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="owner">@lang('Owner Name')<span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" value="{{$bus->owner??null}}" id="owner" class="form-control" placeholder="@lang('Jane Smith')" required/>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="owner_phone">@lang('Owner Phone')<span class="text-danger">*</span></label>
                                <input type="text" name="owner_phone" value="{{$bus->owner_phone??null}}" id="owner_phone" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="brand">@lang('Brand Name')<span class="text-danger">*</span></label>
                                <input type="text" name="brand_name" value="{{$bus->brand_name??null}}" id="brand" class="form-control" placeholder="@lang('Type Here...')" required/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="fleet_type">@lang('Fleet Type')<span class="text-danger">*</span></label>
                                <select class="custom-select" name="fleet_type" id="fleet_type" required>
                                    <option selected value="">@lang('Select One')</option>
                                    @foreach($fleet_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <input type="checkbox" name="status" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" checked>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn--primary">@if(isset($bus)) @lang('Save Changes') @else @lang('Add Bus') @endif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('owner.fleet_manage.bus') }}" class="btn btn-sm btn--dark box--shadow1 text--small">
        <i class="la la-reply"></i>@lang('Back')
    </a>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            @if(isset($bus) && !$bus->status)
            $('.toggle').addClass('btn--danger off').removeClass('btn--success');
            @endif

            @if(isset($bus) && $bus->has_ac)
                $(`input[name=has_ac][value="1"]`).prop("checked", true);

            @elseif(isset($bus) &&  !$bus->has_ac)
                $(`input[name=has_ac][value="0"]`).prop("checked", true);
            @endif

            $('select[name=fleet_type]').val("{{  @$bus->fleet_type_id }}");
        })(jQuery)

    </script>
@endpush



