@extends('admin.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <div class="mb-3">
                    <img src="{{ getImage('assets/owner/images/profile/'. $owner->image, true)}}" alt="@lang('profile-image')" class="b-radius--10 w-100">
                </div>
            </div>
        </div>

        <div class="card mt-30">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Customer Information')</h5>

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Name')
                        <span class="font-weight-bold">{{$owner->owner_name}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Username')
                        <span class="font-weight-bold">{{$owner->username}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @switch($owner->status)
                            @case(1)
                            <span class="badge badge--success font-weight-normal text--small">@lang('Active')</span>
                            @break
                            @case(0)
                            <span class="badge badge--danger font-weight-normal text--small">@lang('Banned')</span>
                            @break
                        @endswitch
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Last Update')
                        <span class="font-weight-bold">{{showDateTime($owner->updated_at,'d M, Y h:i A')}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Joined')
                        <span class="font-weight-bold">{{showDateTime($owner->created_at,'d M, Y h:i A')}}</span>
                    </li>

                </ul>
            </div>
        </div>

        <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Customer Action')</h5>

                <a href="{{ route('admin.users.login.history.single', $owner->id) }}"
                    class="btn btn--primary btn--shadow btn-block btn-lg">
                    @lang('Login Logs')
                </a>
                <a href="{{route('admin.users.email.single',$owner->id)}}"
                    class="btn btn--danger btn--shadow btn-block btn-lg">
                    @lang('Send Email')
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

        <div class="row mb-none-30">
            <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                <div class="dashboard-w1 bg--gradi-1 b-radius--10 box-shadow has--link">
                    <a href="{{route('admin.users.deposits',$owner->id)}}" class="item--link"></a>
                    <div class="icon">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="details">
                        <div class="numbers">
                            <span class="currency-sign"> {{$general->cur_sym}}</span>
                            <span class="amount">{{getAmount($totalDeposit)}}</span>
                        </div>
                        <div class="desciption">
                            <span>@lang('Total Payment')</span>
                        </div>
                    </div>
                </div>
            </div><!-- dashboard-w1 end -->

            <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                <div class="dashboard-w1 bg--gradi-21 b-radius--10 box-shadow has--link">
                    <a href="{{route('admin.users.transactions',$owner->id)}}" class="item--link"></a>
                    <div class="icon">
                        <i class="la la-exchange-alt"></i>
                    </div>
                    <div class="details">
                        <div class="numbers">
                            <span class="amount">{{$totalTransaction}}</span>
                        </div>
                        <div class="desciption">
                            <span>@lang('Total Transaction')</span>
                        </div>
                    </div>
                </div>
            </div><!-- dashboard-w1 end -->

            <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                <div class="dashboard-w1 bg--gradi-11 b-radius--10 box-shadow has--link">
                    <a href="#0" class="item--link"></a>
                    <div class="icon">
                        <i class="la la-exchange-alt"></i>
                    </div>
                    <div class="details">
                        <div class="numbers">
                            <span class="amount">{{ $bought_packages }}</span>
                        </div>
                        <div class="desciption">
                            <span>@lang('Purchased Packages')</span>
                        </div>
                    </div>
                </div>
            </div><!-- dashboard-w1 end -->


        </div>


        <div class="card mt-50">
            <div class="card-body">
                <h5 class="card-title mb-50 border-bottom pb-2">{{$owner->owner_name}} @lang('Information')</h5>

                <form action="{{route('admin.users.update',[$owner->id])}}" method="POST"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Name')<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" value="{{$owner->owner_name}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Email')</label>
                                <input class="form-control bg-white text--black" type="email" name="email" value="{{$owner->email}}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label  font-weight-bold">@lang('Mobile Number') </label>
                                <input class="form-control bg-white text--black" type="text" name="mobile" value="{{$owner->mobile}}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                <input class="form-control" type="text" name="address" value="{{$owner->address->address}}">
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i> @lang('House No.'), @lang('Street Address')
                                </small>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('City') </label>
                                <input class="form-control" type="text" name="city" value="{{$owner->address->city}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('State') </label>
                                <input class="form-control" type="text" name="state" value="{{$owner->address->state}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Zip/Postal')</label>
                                <input class="form-control" type="text" name="zip" value="{{$owner->address->zip}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Country')</label>
                                <select name="country" class="form-control"> @include('partials.country') </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                            <label class="form-control-label font-weight-bold">@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%" name="status" @if($owner->status) checked @endif>
                        </div>

                        <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                            <label class="form-control-label font-weight-bold">@lang('Email Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev" @if($owner->ev) checked @endif>

                        </div>

                        <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                            <label class="form-control-label font-weight-bold">@lang('SMS Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv" @if($owner->sv) checked @endif>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        'use strict';
        (function($){
            $("select[name=country]").val("{{ @$owner->address->country }}");
        })(jQuery)
    </script>
@endpush
