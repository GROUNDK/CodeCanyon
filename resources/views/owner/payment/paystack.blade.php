@extends('owner.layouts.app')

@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
        <div class="card">

            <div class="card-body p-4 text-center">
                <img src="{{$deposit->gateway_currency()->methodImage()}}" alt="@lang('profile-image')" class="user-image custom">

                <h4 class="py-3">
                    @lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}} @lang('Including The Charge')
                </h4>


                <form action="{{ route('ipn.'.$deposit->gateway->alias) }}"
                        method="POST" class="text-center">
                    @csrf
                    <button type="button" class="btn btn--dark btn--capsule btn-block" id="btn-confirm" >@lang('Pay Now')</button>
                    <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}"
                            data-email="{{ $data->email }}" data-amount="{{ $data->amount }}"
                            data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}"
                            data-custom-button="btn-confirm">
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
