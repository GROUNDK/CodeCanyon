@extends('co-owner.layouts.app')

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
                                    <th>@lang('Title')</th>
                                    <th>@lang('AC/Non Ac')</th>
                                    <th>@lang('Day Off')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($trips as $trip)

                            <tr>
                                <td data-label="@lang('S.N.')">{{$trip ->current_page-1 * $trip ->per_page + $loop->iteration }}</td>
                                <td data-label="@lang('Title')"> {{$trip->title}} </td>
                                <td data-label="@lang('AC/Non Ac')">{{ $trip->fleetType->has_ac?trans('Ac'):trans('Non Ac') }}</td>

                                <td data-label="@lang('Day Off')">
                                    @if($trip->day_off)
                                        @foreach ($trip->day_off as $item)
                                            {{ showDayOff($item) }}
                                        @endforeach
                                    @else
                                        @lang('No Off Day')
                                    @endif
                                </td>

                                <td data-label="@lang('Status')">
                                <span class="text--small badge font-weight-normal badge--{{$trip->status?'success':'danger'}}">
                                        {{$trip->status?trans('Active'):trans('Inactive')}}
                                    </span>
                                </td>
                                <td data-label="@lang('Action')">

                                    <a href="@if($trip->trashed())javascript:void(0) @else {{route('co-owner.trip.edit', $trip->id)}}@endif" data-trip="{{ $trip }}" class="icon-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $trip->id }}" class="ml-1 icon-btn btn--{{$trip->trashed()?'success':'danger'}} delete-btn {{ $trip->trashed()?'disabled':'' }}" data-toggle="tooltip" data-placement="top" data-action_type="{{$trip->trashed()?'restore':'delete'}}" title="{{$trip->trashed()?trans('Restore'):trans('Delete')}}">
                                        <i class="la la-trash{{$trip->trashed()?'-restore':''}}"></i>
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
                    {{ $trips->links('admin.partials.paginate') }}
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
    <div class="px-1 col-xl-6 col-lg-12 mb-2 mb-xl-0">
        <form action="" method="GET" class="bg-white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Search...')" value="{{ request()->search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    @if(request()->has('search'))
                        <a href="{{ route('co-owner.trip.index') }}" class="btn btn--dark rounded-right ">
                            @lang('Clear')
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="px-1 col-xl-3 col-lg-12 mb-2 mb-xl-0">
        @if(request()->routeIs('co-owner.trip.index'))
        <a href="{{ route('co-owner.trip.create') }}" class="btn btn--success mr-1 mb-2 mb-xl-0 btn-block">
            <la class="la la-plus"></la>@lang('Add New')
        </a>
        @else
            @if(request()->routeIs('co-owner.trip.trashed.search'))
            <a href="{{route('co-owner.trip.trashed')}}" class="btn btn--dark"><i class="la la-reply"></i>@lang('Back')</a>
            @else
            <a href="{{route('co-owner.trip.index')}}" class="btn btn--dark"><i class="la la-reply"></i>@lang('Back')</a>
            @endif
        @endif
    </div>
    <div class="px-1 col-xl-3 col-lg-12 mb-2 mb-xl-0">
        @if(request()->routeIs('co-owner.trip.index'))
        <a href="{{ route('co-owner.trip.trashed') }}" class="btn btn-danger btn-block"><i class="fas fa-trash-alt"></i>@lang('Trashed')</a>
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
                var id  = $(this).data('id');
                var action_type = $(this).data('action_type');

                if(action_type == 'delete'){
                    modal.find('.modal-body').text("{{ trans('Are you sure to delete this Trip?')}}");
                }else{
                    modal.find('.modal-body').text("{{ trans('Are you sure to restore this Trip?')}}");
                }

                var link  = `{{ route('co-owner.trip.remove', '') }}/${id}`;
                modal.find('form').attr('action', link);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
