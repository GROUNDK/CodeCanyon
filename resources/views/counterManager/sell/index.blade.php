@extends('counterManager.layouts.app')

@section('panel')



@if($active_package->count() == 0 && $general->package_id == null)
    <div class="alert border border--danger bg--white" role="alert">
        <div class="alert__icon bg--danger"><i class="far fa-bell"></i></div>
        <p class="alert__message">@lang('You\'ve no active package. Please contact with the owner to bye a package')</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@else

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action"">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label for="date">@lang('Date of Journey')</label>
                                    <input type="text" name="date_of_journey" class="datepicker-here form-control" data-language='en' data-date-format="yyyy-mm-dd" data-position='bottom left' value="{{ date('Y-m-d') }}" placeholder="@lang('Select Date')" autocomplete="off">

                                    <small class="text--small text--danger"> <i class="fa fa-info-circle" aria-hidden="true"></i> @lang('Year-Month-Date')</small>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from">@lang('From')</label>
                                    <select class="select2-basic" name="from" id="from">
                                        <option value="">@lang('Select One')</option>
                                        @foreach ($counters as $counter)
                                            <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to">@lang('To')</label>
                                    <select class="select2-basic" name="to" id="to">
                                        <option value="">@lang('Select One')</option>
                                        @foreach ($counters as $counter)
                                            <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary float-right">@lang('Go')</button>

                    </form>
                </div>

            </div><!-- card end -->
        </div>
    </div>

@endif
@endsection

@push('script')
    <script>
        'use strict';
        (function($){
            $('.datepicker-here').datepicker().data('datepicker').selectDate(new Date($('.datepicker-here').val()));
        })(jQuery)
    </script>
@endpush

@push('script-lib')
<script type="text/javascript" src="{{ asset('assets/all_vendors/js/vendor/datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/all_vendors/js/vendor/datepicker.en.js') }}"></script>
@endpush


