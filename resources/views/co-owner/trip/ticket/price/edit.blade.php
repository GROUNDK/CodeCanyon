@extends('co-owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div id="overlay">
                        <div class="cv-spinner">
                            <span class="spinner"></span>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($ticket_prices->prices as $item)
                        @php $stoppages = getStoppageInfo($item->source_destination); @endphp
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <form action="{{ route('co-owner.trip.ticket.prices.update', $item->id) }}" method="POST" class="update-form">
                                @csrf
                                @if($item->source_id == $ticket_prices->route->starting_point && $item->destination_id == $ticket_prices->route->destination_point)
                                    <label class="font-weight-bold" for="point-{{$loop->iteration}}"> {{ $stoppages[0]->name }} - {{ $stoppages[1]->name }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="btn--light input-group-text">{{ $owner->general_settings->currency_symbol }}</span>
                                        </div>

                                        <input type="text" name="price" id="point-{{$loop->iteration}}" value="{{$ticket_prices->price}}" class="form-control m-price prices-auto numeric-validation" placeholder="@lang('Enter a price')" required />
                                        <div class="input-group-append">
                                            <button type="submit" class="btn--primary input-group-text update-price">@lang('Update')</button>
                                        </div>
                                    </div>
                                @else

                                    <label for="point-{{$loop->iteration}}"> {{ $stoppages[0]->name }} - {{ $stoppages[1]->name }}</label>
                                    <div class="input-group mb-3">

                                        <div class="input-group-prepend">
                                            <span class="btn--light input-group-text">{{ $owner->general_settings->currency_symbol }}</span>
                                        </div>
                                        <input type="text" name="price" id="point-{{$loop->iteration}}" value="{{$item->price}}" class="form-control prices-auto numeric-validation" placeholder="@lang('Enter a price')" required />

                                        <div class="input-group-append">
                                            <button type="submit" class="btn--primary input-group-text update-price">@lang('Update')</button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
<script>
    'use strict';
    (function($){
        $(document).on('click', '.update-price', function(e){
            e.preventDefault();
            var form = $(this).parents('.update-form');
            var data = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method:"POST",
                data: data,
                success:function(response){
                    if(response.success) {
                        notify('success', response.message);
                    }else{
                        notify('error', response.message);
                    }
                }
            });
        });
    })(jQuery)
</script>
@endpush


