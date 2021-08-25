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

                    <button type="button" class="btn-block btn--dark  btn--capsule  btn-lg" id="btn-confirm">@lang('Pay Now')</button>
            </div>
        </div>
    </div>
</div>
@endsection



@push('script')

    <script src="//voguepay.com/js/voguepay.js"></script>
    <script>
        'use strict';
        var closedFunction = function() {
        }
        var successFunction = function(transaction_id) {
            window.location.href = '{{ route('owner.deposit') }}';
        }
        var failedFunction = function(transaction_id) {
            window.location.href = '{{ route('owner.deposit') }}' ;
        }

        function pay(item, price) {
            //Initiate voguepay inline payment
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id}}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{$data->cur}}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo:"{{$data->memo}}",
                recurrent: true,
                frequency: 10,
                developer_code: '5af93ca2913fd',
                store_id:"{{ $data->store_id }}",
                custom: "{{ $data->custom }}",

                closed:closedFunction,
                success:successFunction,
                failed:failedFunction
            });
        }

        (function($){
            $(document).on('click', '#btn-confirm', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });
        })(jQuery)
    </script>
@endpush
