@extends('co-owner.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage('assets/owner/images/coOwner/'. $co_owner->image, true)}}" alt="profile-image">
                        </div>
                        <div class="pl-3">
                            <h4 class="text--white">{{$co_owner->name}}</h4>
                        </div>
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Owner Name')
                            <span class="font-weight-bold">{{$co_owner->owner->owner_name}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="font-weight-bold">{{$co_owner->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="font-weight-bold">{{$co_owner->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile')
                            <span  class="font-weight-bold">{{$co_owner->mobile}}</span>
                        </li>
                        @if(@$co_owner->address->address != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address')
                            <span  class="font-weight-bold">{{$co_owner->address->address}}</span>
                        </li>
                        @endif
                        @if(@$co_owner->address->state != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            <span  class="font-weight-bold">{{$co_owner->address->state}}</span>
                        </li>
                        @endif
                        @if(@$co_owner->address->zip != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Zip')
                            <span  class="font-weight-bold">{{$co_owner->address->zip}}</span>
                        </li>
                        @endif
                        @if(@$co_owner->address->city != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span  class="font-weight-bold">{{$co_owner->address->city}}</span>
                        </li>
                        @endif
                        @if(@$co_owner->address->country != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            <span  class="font-weight-bold">{{$co_owner->address->country}}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Change Password')</h5>
                    <form action="{{ route('co-owner.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Password')</label>
                            <input class="form-control" type="password" name="old_password">
                        </div>

                        <div class="form-group">
                            <label>@lang('New Password')</label>
                                <input class="form-control" type="password" name="password">
                        </div>

                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation">
                        </div>
                        <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('co-owner.profile')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-user"></i>@lang('Profile Setting')</a>
@endpush
