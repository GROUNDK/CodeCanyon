@extends('owner.layouts.app')

@section('panel')


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.trip.route.store', $route->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{$route->name??null}}" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="starting_point"> @lang('Starting Point') <span class="text-danger">*</span></label>
                                    <select class="select2-basic" name="starting_point" id="starting_point" required>
                                        <option selected>@lang('Select One')</option>
                                        @foreach ($allStoppages as $stoppage)
                                        <option value="{{$stoppage->id}}">{{$stoppage->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination_point">@lang('Destination Point') <span class="text-danger">*</span></label>
                                    <select class="select2-basic" name="destination_point" id="destination_point" required>
                                        <option selected>@lang('Select One')</option>
                                        @foreach ($allStoppages as $stoppage)
                                        <option value="{{$stoppage->id}}">{{$stoppage->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox form-check-primary">
                            <input type="checkbox" class="custom-control-input" id="has-stoppage" {{count($stoppages)>0?'checked':''}}/>
                                <label class="custom-control-label" for="has-stoppage">@lang('Has More Stoppage')</label>
                            </div>
                        </div>

                        <div class="stoppages-wrapper">

                            <div class="row stoppages-row">
                            @foreach ($stoppages as $item)

                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $loop->iteration }}</span>
                                        </div>
                                        <select class="select2-basic form-control w-auto" name="stoppages[{{ $loop->iteration }}]" required >
                                            <option value="" selected>@lang('Select Stoppage')</option>
                                            @foreach ($allStoppages as $stoppage)
                                            <option value="{{$stoppage->id}}" {{ $item->id == $stoppage->id?'selected' : '' }}>{{$stoppage->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text bg-danger border--danger remove-stoppage"><i class="la la-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>

                            @if(count($stoppages)>0)
                                <button type="button" class="btn btn-sm btn--success add-stoppage-btn mb-1"><i class="la la-plus"></i> @lang('Next Stoppage')</button> <span class="text--danger"> @lang('Make sure that you are adding stoppages serially followed by the starting point')</span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="distance">@lang('Distance from Soruce to Destination')</label>
                            <input type="text" name="distance" value="{{$route->distance??null}}" id="distance" class="form-control" placeholder="@lang('50 Miles')"/>
                            <small class="text-danger">@lang('Keep SPACE between value and unit')</small>
                        </div>

                        <div class="form-group">
                            <label for="time">@lang('Time Approximate')</label>
                            <input type="text" name="time" value="{{$route->time??null}}" id="time" class="form-control" placeholder="@lang('3 Hour')"/>
                            <small class="text-danger">@lang('Keep SPACE between value and unit')</small>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" @if(isset($route))@if($route->status == 1) checked @endif @else checked @endif>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@if(!isset($route))@lang('Add Route') @else @lang('Save Changes') @endif</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <a href="{{route('owner.trip.route')}}" class="btn btn-sm btn--dark text--small box--shadow1"> <i class="las la-reply"></i>@lang('Back')</a>
@endpush

@push('script')
    <script>

        'use strict';
        (function($){
            var stoppages = JSON.parse('@php echo json_encode($route->stoppages) @endphp');

            $('select[name=starting_point]').val('{{$route->starting_point}}');
            $('select[name=destination_point]').val('{{$route->destination_point}}');

            $('.select2-basic').select2({
                dropdownParent: $('.card-body')
            });


            $('#has-stoppage').on('click', function() {
                if(this.checked){

                    var stps =
                            `<div class="row stoppages-row">
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">1</span>
                                        </div>
                                        <select class="select2-basic form-control w-auto" name="stoppages[1]" required >
                                            <option value="" selected>@lang('Select Stoppage')</option>
                                            @foreach ($allStoppages as $stoppage)
                                            <option value="{{$stoppage->id}}">{{$stoppage->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn--success add-stoppage-btn mb-1"><i class="la la-plus"></i>@lang('Next Stoppage')</button> <span class="text--danger"> @lang('Make sure that you are adding stoppages serially followed by the starting point')</span>

                            `;

                    $('.stoppages-wrapper').prepend(stps);
                    $('.select2-basic').select2({
                        dropdownParent: $('.card-body')
                    });
                }else{
                    $('.stoppages-wrapper').html('');

                }
            });

            $(document).on('click', '.add-stoppage-btn', function(){
                var elements = $('.stoppages-row .col-md-3');
                $(elements).each(function (index, element) {
                    $(element).find('.select2-basic').attr('name',`stoppages[${index+1}]`);

                });

                var itr = elements.length;

                var stps = `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">${itr +1}</span>
                                    </div>
                                    <select class="select2-basic form-control w-auto" name="stoppages[${itr + 1}]">
                                        <option value="" selected>@lang('Select Stoppage')</option>
                                        @foreach ($allStoppages as $stoppage)
                                        <option value="{{$stoppage->id}}">{{$stoppage->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>`;

                $('.stoppages-row').append(stps);

                $('.select2-basic').select2({
                    dropdownParent: $('.card-body')
                });

                $($('.stoppages-row .col-md-3')).each(function (index, element) {

                    $(element).find('.input-group-prepend > .input-group-text').text(index+1);

                });
            });

            $(document).on('click', '.remove-stoppage', function() {
                $(this).closest('.col-md-3').remove();
                var elements = $('.stoppages-row .col-md-3').find();

                $($('.stoppages-row .col-md-3')).each(function (index, element) {

                    $(element).find('.input-group-prepend > .input-group-text').text(index+1);
                    $(element).find('.select2-basic').attr('name',`stoppages[${index+1}]`);

                });
            });
        })(jQuery)

    </script>

@endpush
