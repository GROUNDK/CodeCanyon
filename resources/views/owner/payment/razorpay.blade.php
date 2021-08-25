@extends('owner.layouts.app')
@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card">
            <div class="card-body p-4 text-center">
                <img src="{{$deposit->gateway_currency()->methodImage()}}" alt="@lang('profile-image')" class="user-image custom">
                <h4 class="py-3">
                    @lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}} @lang('Including The Charge')
                </h4>

                <form action="{{$data->url}}" method="{{$data->method}}">
                <script src="{{$data->checkout_js}}"
                    @foreach($data->val as $key=>$value)
                        data-{{$key}}="{{$value}}"
                    @endforeach >
                </script>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('input[type="submit"]').addClass("btn btn--capsule btn-block btn--dark");
        })(jQuery);
    </script>
@endpush
