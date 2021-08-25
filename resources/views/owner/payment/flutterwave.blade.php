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

                <button type="button" class="btn btn-block btn--dark btn--capsule" id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>

                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

    <script>
        'use strict';
        var btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{$data->API_publicKey}}";

        function payWithRave() {
            var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{$data->customer_email}}",
                amount: "{{$data->amount }}",
                customer_phone: "{{$data->customer_phone}}",
                currency: "{{$data->currency}}",
                txref: "{{$data->txref}}",
                onclose: function () {
                },
                callback: function (response) {
                    var txref = response.tx.txRef;
                    var status = response.tx.status;
                    var chargeResponse = response.tx.chargeResponseCode;
                    if (chargeResponse == "00" || chargeResponse == "0") {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    } else {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    }
                }
            });
        }
    </script>
            </div>
        </div>
    </div>
</div>
@endsection
