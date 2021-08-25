@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Date')</th>
                                <th>@lang('Order No.')</th>
                                <th>@lang('TRX')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Package')</th>
                                <th>@lang('Price')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td data-label="@lang('Date')">{{ showDateTime($sale->created_at) }}</td>
                                    <td data-label="@lang('Order No.')" class="font-weight-bold">{{ $sale->order_number }}</td>
                                    <td data-label="@lang('TRX')" class="font-weight-bold">{{ $sale->deposit->trx }}</td>
                                    <td data-label="@lang('Username')"><a href="{{ route('admin.users.detail', $sale->owner_id) }}">{{ @$sale->owner->username }}</a></td>
                                    <td data-label="@lang('Package')">{{ __($sale->package->name) }}</td>
                                    <td data-label="@lang('Amount')" class="budget">
                                        <strong> {{getAmount($sale->price)}} {{$general->cur_text}}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $sales->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <form action="{{ route('admin.report.sales.search') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Order No. / Username')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


