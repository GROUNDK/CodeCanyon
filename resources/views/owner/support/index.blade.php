@extends('owner.layouts.app')

@section('panel')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--sm">
                            <table class="table table--light">
                                <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($supports as $key => $support)
                                        <tr>
                                            <td data-label="@lang('Subject')"> <a href="{{ route('owner.ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ $support->subject }} </a></td>
                                            <td data-label="@lang('Status')">
                                                @if($support->status == 0)
                                                    <span class="text--small font-weight-normal badge--success">@lang('Open')</span>
                                                @elseif($support->status == 1)
                                                    <span class="text--small font-weight-normal badge--primary">@lang('Answered')</span>
                                                @elseif($support->status == 2)
                                                    <span class="text--small font-weight-normal badge--warning">@lang('Customer Reply')</span>
                                                @elseif($support->status == 3)
                                                    <span class="text--small font-weight-normal badge--dark">@lang('Closed')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                            <td data-label="@lang('Action')">
                                                <a href="{{ route('owner.ticket.view', $support->ticket) }}" class="icon-btn btn--primary">
                                                    <i class="fa fa-desktop"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">{{ __($empty_message) }}</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        {{$supports->links('admin.partials.paginate')}}
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('owner.ticket.open')}}" class="btn btn-sm btn--success box--shadow1 text--small" ><i class="fa fa-plus"></i>@lang('New Ticket')</a>
@endpush
