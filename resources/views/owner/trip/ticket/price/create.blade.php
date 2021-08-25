@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.trip.ticket.price.store') }}" method="POST">
                        <div id="overlay">
                            <div class="cv-spinner">
                                <span class="spinner"></span>
                            </div>
                        </div>
                        @csrf

                        <div class="form-group">
                            <label for="fleet_type">@lang('Fleet Type')<span class="text-danger">*</span> </label>
                            <select class="select2-basic" name="fleet_type" id="fleet_type" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($fleet_types as $fleet_type)
                                <option value="{{$fleet_type->id}}"> {{$fleet_type->name}} - {{$fleet_type->has_ac?trans('Ac'):trans('Non Ac')}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="route">@lang('Route')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="route" id="route" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($routes as $route)
                                <option value="{{$route->id}}">{{$route->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="price-error-message">

                        </div>


                        <label for="price">@lang('Price for Source to Destination')<span class="text-danger">*</span> </label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="btn--light input-group-text">{{ @$owner->general_settings->currency_symbol }}</span>
                            </div>
                            <input type="text" class="form-control numeric-validation main_price" name="main_price" id="price" placeholder="@lang("Type Here...")" required/>
                        </div>

                        <div class="price-wrapper">

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary submit-button">@lang('Set Ticket Price')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{route('owner.trip.ticket.price')}}" class="btn btn-sm btn--dark"><i class="la la-reply"></i>@lang('Back')</a>
@endpush

@push('script')
    <script>

        'use strict';
        (function($){
            @if(request()->routeIs('owner.trip.edit'))
                $('select[name=fleet_type]').val('{{$trip->fleet_type_id}}');
                $('select[name=route]').val('{{$trip->route_id}}');

                $('.select2-basic, .select2-multi-select').select2({
                    dropdownParent: $('.card-body')
                });
            @endif

            $(document).on('change', 'select[name=route], select[name="fleet_type"]', function () {
                var routeId        = $('select[name="route"]').find("option:selected").val();
                var fleetTypeId   = $('select[name="fleet_type"]').find("option:selected").val();

                if(routeId && fleetTypeId){
                    var data = {
                        'route_id'      : routeId,
                        'fleet_type_id' : fleetTypeId
                    }
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{ route('owner.trip.ticket.get_route_data') }}",
                        method: "get",
                        data: data,
                        success: function(result){
                            if(result.error){
                                $('.price-error-message').html(`<h5 class="text--danger">${result.error}</h5>`);
                                $('.submit-button').attr('disabled', 'disabled');
                            }else{
                                $('.price-error-message').html(``);
                                $('.submit-button').removeAttr('disabled');
                                $('.price-wrapper').html(`<h5>${result}</h5>`);
                            }
                        }
                    }).done(function() {
                        setTimeout(function(){
                            $("#overlay").fadeOut(300);
                        },500);
                    });
                }
            });

            $(document).on('input', '.main_price' ,function () {
                var price = $(this).val();
                $(document).find('.prices-auto').val(price);
            });

        })(jQuery)

    </script>
@endpush
