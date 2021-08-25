@extends('owner.layouts.app')


@section('panel')

    <div class="row">
        @foreach($gatewayCurrency as $data)
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card card-deposit">
                    <h5 class="card-header text-center bg--10">{{__($data->name)}}
                    </h5>
                    <div class="card-body card-body-deposit">
                        <img src="{{$data->methodImage()}}" class="card-img-top" alt="{{__($data->name)}}" style="width: 100%; min-height: 213px; ">
                    </div>
                    <div class="card-footer">
                        <form action="{{route('owner.deposit.insert')}} " method="POST">
                            @csrf
                            <input type="hidden" name="currency" class="edit-currency" value="{{__($data->currency)}}">
                            <input type="hidden" name="method_code" class="edit-method-code" value="{{__($data->method_code)}}">
                            <button type="submit" class="btn btn-block btn--dark btn--shadow-default custom-success btn--capsule">@lang('Pay Now')</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
