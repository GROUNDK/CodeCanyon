@extends('owner.layouts.app')


@section('panel')

    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-4 col-lg-6 ">
            <div class="card">

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-center align-items-center">
                        <img src="{{ $data->gateway_currency()->methodImage() }}" class=" p-3" alt="..."/>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Amount')
                        <span>{{getAmount($data->amount)}}  {{$general->cur_text}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Charge')
                        <span>{{getAmount($data->charge)}} {{$general->cur_text}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Payable') <span> {{getAmount($data->amount + $data->charge)}} {{$general->cur_text}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Conversion Rate') <span> 1 {{$general->cur_text}} = {{getAmount($data->rate)}}  {{$data->baseCurrency()}} </span>
                    </li>


                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang("In "){{ $data->baseCurrency() }}
                        <span>{{getAmount($data->final_amo)}}</span>
                    </li>


                    @if($data->gateway->crypto==1)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("Conversion with $data->method_currency and final value will Show on next step")
                        </li>
                    @endif

                    <li class="list-group-item">
                        @if( 1000 >$data->method_code)
                            <a href="{{route('owner.deposit.confirm')}}" class="btn btn--dark btn-block btn--capsule font-weight-bold float-right">@lang('Pay Now')</a>
                        @else
                            <a href="{{route('owner.deposit.manual.confirm')}}" class="btn btn-block btn--capsule btn--dark font-weight-bold float-right">@lang('Pay Now')</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection


