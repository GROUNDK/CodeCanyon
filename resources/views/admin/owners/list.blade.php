@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Phone')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($owners as $owner)
                            <tr>
                                <td data-label="@lang('User')">
                                    <div class="user">
                                        <div class="thumb">
                                            <a href="{{ getImage('assets/owner/images/profile/'. $owner->image, true)}}" class="image-popup">
                                                <img src="{{ getImage('assets/owner/images/profile/'. $owner->image, true)}}" alt="image">
                                            </a>
                                        </div>
                                        <span class="name">{{$owner->fullname}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Username')"><a href="{{ route('admin.users.detail', $owner->id) }}">{{ $owner->username }}</a></td>
                                <td data-label="@lang('Email')">{{ $owner->email }}</td>
                                <td data-label="@lang('Phone')">{{ $owner->mobile }}</td>
                                <td data-label="@lang('Joined At')">{{ showDateTime($owner->created_at) }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.users.detail', $owner->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
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
                    {{ $owners->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.users.search', $scope ?? str_replace('admin.users.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or Email')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('.image-popup').magnificPopup({
                type: 'image'
            });
        })(jQuery)
    </script>
@endpush
