@extends('owner.layouts.app')

@section('panel')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-9">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <ul class="list-group rounded-0">
                        <li class="list-group-item d-flex justify-content-between bg--10">
                            <span class="font-weight-bold text-white">@lang('Ticket Number')</span>
                            <span class="text--danger font-weight-bolder">{{ sprintf('%06d', $sale->id) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Booked at')</span>
                            <span class="font-italic text-color--10">{{ showDateTime($sale->created_at, 'M d, Y h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Date of Journey')</span>
                            <span class="font-weight-bold font-italic text-color--10">{{ showDateTime($sale->date_of_journey, 'M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Passenger Name')</span>
                            <span class="text--deep-purple font-weight-bold">{{ $sale->passenger_details['name'] }} ({{ showGender($sale->passenger_details['gender']) }})</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Passenger E-mail')</span>
                            <span class="font-italic text-color--10">{{ @$sale->passenger_details['email'] }}</span>
                        </li>
                        @if(isset($sale->passenger_details['mobile_number']) && $sale->passenger_details['mobile_number'] != '')
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Passenger Mobile No.')</span>
                            <span class="font-italic text-color--10">{{ $sale->passenger_details['mobile_number'] }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Pickup Point')</span>
                            <span class="font-italic text-color--10">{{ @$sale->passenger_details['from'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Dropping Point')</span>
                            <span class="font-italic text-color--10">{{ @$sale->passenger_details['to'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Booked Seats')</span>
                            <span class="font-italic text-color--10">
                                @foreach ($sale->seats as $item)
                                    <span class="bg--10 px-2 py-1 rounded-pill">{{ $item }}</span>
                                @endforeach
                            </span>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <span class="font-weight-bold col-2">@lang('Trip')</span>
                                <span class=" font-italic text-color--10 col-10 text-right">{{ $sale->trip->title }}</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">

                                <span class="font-weight-bold">@lang('Price/Ticket')</span>
                                <span class="font-weight-bold text--cyan">{{ $owner->general_settings->currency_symbol }}{{ $sale->price }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">

                            <span class="font-weight-bold">@lang('Number of Ticket')</span>
                            <span class="font-weight-bold font-italic text--cyan">{{ $sale->ticket_count }}</span>

                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="font-weight-bold">@lang('Booked By')</span>
                            <span class="text--deep-purple font-weight-bold"> {{ $sale->counterManager->name }} </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg--10">
                            <span class="font-weight-bold text-white">@lang('Total Amount')</span>
                            <span class="font-weight-bold text--danger">{{ $owner->general_settings->currency_symbol }}{{  getAmount($sale->ticket_count *  $sale->price)  }} </span>
                        </li>
                    </ul>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('owner.report.sale') }}" class="btn btn-sm btn--dark text--small box--shadow1"><i class="la la-reply"></i>@lang('Back')</a>
@endpush


