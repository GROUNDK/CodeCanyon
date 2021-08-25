@extends('owner.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body">
                <form action="{{ route('owner.fleet_manage.fleet_type.store', $fleet_type->id??0) }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang('Name')<span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{$fleet_type->name??null}}" id="name" class="form-control" placeholder="@lang('Classic / Vip / Royal')" required/>
                    </div>

                    <div class="form-group">
                        <label for="seat_layout">@lang('Seat Layout')</label>
                        <select class="custom-select" name="seat_layout" id="seat_layout" required>
                            <option selected value="">@lang('Seat One')</option>
                            @foreach($seat_layouts as $item)
                                <option value="{{ $item->layout }}">{{ $item->layout }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deck">@lang('Number of Deck')<span class="text-danger">*</span></label>
                        <input type="text" name="number_of_deck" value="{{$fleet_type->deck??null}}" id="deck" class="form-control integer-validation" placeholder="@lang('1 / 2')" autocomplete="off" required />
                    </div>

                    <div class="seat-number-wrapper">
                        @if(request()->routeIs('owner.fleet_manage.fleet_type.edit'))
                        <div class="row">
                            @foreach( $fleet_type->seats as $item)
                            <div class="form-group col-lg-4 col-md-3 col-sm-6">
                                <label for="seat[{{ $loop->iteration }}]">@lang('Seat Number for Deck '){{ $loop->iteration }} <span class="text-danger">*</span></label>
                                <input type="text" name="seats[{{ $loop->iteration }}]" value="{{ $item }}" id="seat" class="form-control integer-validation" placeholder="@lang('100')" autocomplete="off" required/>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <label class="d-block">@lang('Has Ac')</label>
                    <div class="radio-box-wrapper d-flex flex-wrap">
                        <div class="form-radio-box mr-3">
                            <input type="radio" id="has_ac_1" value="1" name="has_ac" >
                            <label for="has_ac_1">@lang('Yes')</label>
                        </div>
                        <div class="form-radio-box">
                            <input type="radio" id="has_ac_0" value="0" name="has_ac">
                            <label for="has_ac_0">@lang('No')</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label>@lang('Status')</label>
                            <input type="checkbox" name="status" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" checked>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn--primary">@if(isset($fleet_type)) @lang('Save Changes') @else @lang('Add Fleet Type') @endif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('owner.fleet_manage.fleet_type') }}" class="btn btn--dark">
        <i class="la la-reply"></i>@lang('Back')
    </a>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            @if(isset($fleet_type) && !$fleet_type->status)
            $('.toggle').addClass('btn--danger off').removeClass('btn--success');
            @endif

            @if(isset($fleet_type) && $fleet_type->has_ac)
                $(`input[name=has_ac][value="1"]`).prop("checked", true);

            @elseif(isset($fleet_type) &&  !$fleet_type->has_ac)
                $(`input[name=has_ac][value="0"]`).prop("checked", true);
            @endif

            $('select[name=seat_layout]').val("{{  @$fleet_type->seat_layout }}");

            $(document).on('keyup', 'input[name=number_of_deck]', function(){
                var deckNumber = $(this).val();

                var i = 1;
                var fields =`<div class="row">`;

                for (i; i <= deckNumber; i++ ){
                    fields +=`<div class="form-group col-lg-4 col-md-3 col-sm-6">
                                <label for="seat[${i}]">@lang('Seat Number for Deck') ${i} <span class="text-danger">*</span></label>
                                <input type="text" name="seats[${i}]" id="seat" class="form-control integer-validation" placeholder="@lang('100')" autocomplete="off" required/>
                            </div>`;
                }

                fields +=`<div class="/row">`;
                $('.seat-number-wrapper').html(fields);
            });
        })(jQuery)
    </script>
@endpush



