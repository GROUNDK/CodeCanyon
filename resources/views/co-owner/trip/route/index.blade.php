@extends('co-owner.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--md table-responsive">
                        <table class="default-data-table table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Starting Point')</th>
                                    <th>@lang('Destination Point')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($routes as $route)
                                <tr>
                                    <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                    <td data-label="@lang('Name')">{{ $route->name }}</td>

                                    <td data-label="@lang('Starting Point')">{{ $route->startingPoint->name }}</td>
                                    <td data-label="@lang('Destinaton Point')">{{ $route->destinationPoint->name }}</td>

                                    <td data-label="@lang('Status')">
                                    <span class="text--small badge font-weight-normal badge--{{ $route->status?'success':'danger' }}">
                                            {{$route->status?trans('Active'):trans('Inactive')}}
                                        </span>
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('co-owner.trip.route.edit', $route->id)}}" class="icon-btn dit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                            <i class="la la-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%"> {{ __($empty_message) }} </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- card end -->
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{route('co-owner.trip.route.create')}}" class="btn btn-sm btn--success text--small box--shadow1">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush



