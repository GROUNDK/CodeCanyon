@extends('owner.layouts.app')
@push('style')
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .stripe-button-el span, .stripe-button-el {
            background: unset !important;
            background-image: none !important;
            background-color: #10163A !important;
        }


    </style>
@endpush

@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
        <div class="card">
            <div class="card-body p-4 text-center">
                <img src="{{$deposit->gateway_currency()->methodImage()}}" alt="@lang('profile-image')" class="user-image custom">
                <h4 class="py-3">
                    @lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}} @lang('Including The Charge')
                </h4>
                <form action="{{$data->url}}" method="{{$data->method}}">

                <script src="{{$data->src}}" class="stripe-button"
                    @foreach($data->val as $key=> $value)
                    data-{{$key}}="{{$value}}"
                    @endforeach>
                </script>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="https://js.stripe.com/v3/"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('.stripe-button-el').addClass("btn btn--capsule btn-block");
        })(jQuery);
    </script>
@endpush
