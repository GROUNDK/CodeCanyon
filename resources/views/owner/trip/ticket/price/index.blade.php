@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Fleet Type')</th>
                                    <th>@lang('Route')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($ticket_prices as $tp)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $tp->current_page-1 * $tp->per_page + $loop->iteration }}</td>
                                <td data-label="@lang('Fleet Type')"> {{ $tp->fleetType->name }} </td>
                                <td data-label="@lang('Route')"> {{ @$tp->route->name }} </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{route('owner.trip.ticket.price.edit', $tp->id)}}" data-trip="{{ $tp }}" class="icon-btn edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $tp->id }}" class="ml-1 icon-btn btn--danger delete-btn" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                                        <i class="la la-trash"></i>
                                    </a>
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
                    {{ $ticket_prices->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>


    <!-- Removal Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5 class="font-weight-bold mb-2">@lang('Are you sure to delete this?')?</h5>
                        <div class="text-danger ">
                            <i class="las la-exclamation-triangle"></i>
                            @lang('Caution: If you delete this all prices for stoppage to stoppage will also be removed')
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<div class="row d-flex flex-row-reverse p-2">
    <div class="px-1 col-xl-3 col-lg-12 mb-2 mb-xl-0">
        @if(request()->routeIs('owner.trip.ticket.price'))
        <a  href="{{route('owner.trip.ticket.price.create')}}" class="btn btn--success mr-1 mb-2 mb-xl-0 add-btn text--small"><i class="la la-plus"></i>@lang('Add New')</a>
        @else
            <a href="{{route('owner.trip.ticket.price')}}" class="btn btn-dark"><i class="la la-reply"></i>@lang('Back')</a>
        @endif
    </div>
</div>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id      = $(this).data('id');
                var link  = `{{ route('owner.trip.ticket.price.remove', '') }}/${id}`;

                modal.find('form').attr('action', link);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush


