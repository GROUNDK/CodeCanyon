@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.trip.store', $trip->id??0) }}" method="POST">
                        @csrf
                        <div id="overlay">
                            <div class="cv-spinner">
                                <span class="spinner"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title">@lang('Title')<span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{$trip->title??null}}" readonly required/>
                        </div>

                        <div class="form-group ">
                            <label for="fleet_type">@lang('Fleet Type')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="fleet_type" id="fleet_type" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($fleet_types as $ft)
                                <option value="{{$ft->id}}" data-name="{{$ft->name}}">{{$ft->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="route">@lang('Route')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="route" id="route" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($routes as $route)
                                <option value="{{$route->id}}" data-name="{{$route->name}}" data-source_id="{{$route->starting_point}}" data-source="{{$route->startingPoint->name}}" data-destination_id="{{$route->destination_point}}" data-destination="{{$route->destinationPoint->name}}">{{$route->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="from-to-wrapper">
                            @if(request()->routeIs('owner.trip.edit'))
                            <div class="form-group">
                                <label for="from">@lang('From')</label>
                                <select class="form-control" name="from" id="from" required>
                                    <option value="{{$trip->starting_point}}" data-name="{{$trip->startingPoint->name}}">{{$trip->startingPoint->name}}</option>
                                    <option value="{{$trip->destination_point}}" data-name="{{$trip->destinationPoint->name}}">{{$trip->destinationPoint->name}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="to">@lang('To')</label>
                                <span class="value-change-button">
                                    <i class="transform-rotate-90 las la-exchange-alt"></i>
                                </span>

                                <select class="form-control" name="to" id="to" required>
                                    <option value="{{$trip->starting_point}}" data-name="{{$trip->startingPoint->name}}">{{$trip->startingPoint->name}}</option>
                                    <option value="{{$trip->destination_point}}" data-name="{{$trip->destinationPoint->name}}">{{$trip->destinationPoint->name}}</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        <div class="form-group ">
                            <label for="edit-schedule">@lang('Schedule')<span class="text-danger">*</span></label>
                            <select class="select2-basic" name="schedule" id="edit-schedule" required>
                                <option selected value="">@lang('Select One')</option>
                                @foreach ($schedules as $schedule)
                                <option value="{{$schedule->id}}" data-name="{{ showDateTime($schedule->starts_from, 'H:i a') }} - {{showDateTime($schedule->ends_at, 'H:i a')}}">{{ showDateTime($schedule->starts_from, 'H:i a') }} - {{showDateTime($schedule->ends_at, 'H:i a')}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="day_off">@lang('Day Off')</label>
                            <select class="select2-multi-select" name="day_off[]" id="day_off" multiple="multiple required">
                                <option value="0">@lang('Sunday')</option>
                                <option value="1">@lang('Monday')</option>
                                <option value="2">@lang('Tuesday')</option>
                                <option value="3">@lang('Wednesday')</option>
                                <option value="4">@lang('Thursday')</option>
                                <option value="5">@lang('Friday')</option>
                                <option value="6">@lang('Saturday')</option>
                            </select>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label>@lang('Status')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@if(!isset($driver)) @lang('Add Trip') @else @lang('Save Changes') @endif</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Help Message')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<div class="row d-flex flex-row-reverse">
    <a href="{{route('owner.trip.index')}}" class="btn btn--dark"><i class="la la-reply"></i>@lang('Back')</a>
</div>
@endpush

@push('script')
<script>
    'use strict';
    (function($){

        $('.select2-basic, .select2-multi-select').select2({
            dropdownParent: $('.card-body')
        });

        @if(request()->routeIs('owner.trip.edit'))
            var day_off = JSON.parse('@php echo json_encode($trip->day_off) @endphp');

            console.log(day_off);
            $(function () {
                $('select[name=fleet_type]').val('{{$trip->fleet_type_id}}');
                $('select[name=route]').val('{{$trip->route_id}}');
                $('select[name=schedule]').val('{{$trip->schedule_id}}');
                $('select[name=from]').val('{{$trip->starting_point}}');
                $('select[name=to]').val('{{$trip->destination_point}}');
                $('select[name="day_off[]"]').val(day_off);

                $('.select2-basic, .select2-multi-select').select2({
                    dropdownParent: $('.card-body')
                });
            });
        @endif

        $(document).on('change', 'select[name="route"]', function () {
            var source          = $(this).parents('.card-body').find('select[name="route"]').find("option:selected").data('source');
            var source_id       = $(this).parents('.card-body').find('select[name="route"]').find("option:selected").data('source_id');
            var destination     = $(this).parents('.card-body').find('select[name="route"]').find("option:selected").data('destination');
            var destination_id  = $(this).parents('.card-body').find('select[name="route"]').find("option:selected").data('destination_id');

            var contents = `
                            <div class="form-group">
                                <label for="from">@lang('From')</label>

                                <select class="form-control" name="from" id="from" required>
                                    <option value="${source_id}" data-name="${source}" selected>${source}</option>
                                    <option value="${destination_id}" data-name="${destination}">${destination}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="to">@lang('To')</label>
                                <span class="value-change-button">
                                    <i class="transform-rotate-90 las la-exchange-alt"></i>
                                </span>

                                <select class="form-control" name="to" id="to" required>
                                    <option value="${source_id}" data-name="${source}">${source}</option>
                                    <option value="${destination_id}" data-name="${destination}" selected>${destination}</option>
                                </select>

                            </div>
                            `;
            $('.from-to-wrapper').fadeIn("slow").html(contents);
        });

        $(document).on('change', 'select[name="fleet_type"], select[name="schedule"], select[name="route"], select[name="from"], select[name="to"]', function () {

            makeTitle();
        });

        $(document).on('click','.value-change-button' ,function (e) {
            var from    = $('select[name="from"]').val();
            var to      = $('select[name="to"]').val();

            $('select[name="from"]').val(to);

            $('select[name="to"]').val(from);

            makeTitle();
        });

        function makeTitle(){
            var data1 = $('.card-body').find('select[name="fleet_type"]').find("option:selected").data('name');
            var data2 = $('.card-body').find('select[name="route"]').find("option:selected").data('name');
            var data4 = $('.card-body').find('select[name="from"]').find("option:selected").data('name');
            var data5 = $('.card-body').find('select[name="to"]').find("option:selected").data('name');
            var data3 = $('.card-body').find('select[name="schedule"]').find("option:selected").data('name');
            var data  = [];
            var title = '';

            if(data1 != undefined)
                data.push(data1);

            if(data2 != undefined)
                data.push(data2);

            if(data3 != undefined)
                data.push(data3);

            if(data4 != undefined)
                data.push(data4);

            if(data5 != undefined)
                data.push(data5);

            if(data1 != undefined && data2 != undefined) {
                $("#overlay").fadeIn(300);
                $.ajax({
                    type: "get",
                    url: "{{ route('owner.trip.ticket.check_price') }}",
                    data: {
                        "fleet_type_id" : $('.card-body').find('select[name="fleet_type"]').val(),
                        "route_id" : $('.card-body').find('select[name="route"]').val()
                    },
                    success: function (response) {
                        if(response.error){
                            var modal = $('#alertModal');
                            modal.find('.container-fluid').text(response.error);
                            modal.modal('show');
                        }
                    }
                }).done(function() {
                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);
                });
            }

            $.each(data, function (index, value) {
                if(index > 0){
                    if(index > 3)
                        title += ' to ';
                    else
                        title += ' - ';

                }
                title += value;
            });
            $('input[name="title"]').val(title);
        }

        $("#alertModal").on('hidden.bs.modal', e => {
            $('.card-body').find('select, input').val('');
        });
    })(jQuery)


    </script>
@endpush

