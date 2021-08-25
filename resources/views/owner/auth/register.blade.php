@extends('layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/all_vendors/images/1.jpg')}}">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">@lang("$page_title to  $general->sitename")</h4>
                <p>@lang('Create a new account as an Owner')</p>
                <form action="{{ route('owner.register') }}" method="POST" class="cmn-form mt-30">
                    @csrf
                    <div class="form-group">
                        <label for="owner_name">@lang('Owner Name')</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control b-radius--capsule" value="{{ old('owner_name') }}" placeholder="@lang('Enter Owner Name')">
                        <i class="las la-user input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="username">@lang('Username')</label>
                        <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" placeholder="@lang("Enter Username")">
                        <i class="las la-user-astronaut input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang("Email")</label>
                        <input type="email" name="email" class="form-control b-radius--capsule" id="email" value="{{ old('email') }}" placeholder="@lang('Enter Email')">
                        <i class="las la-at input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="country">@lang("Country")</label>
                        <select class="form-control b-radius--capsule select2-basic" id="country" name="country" class="mb-3">
                            @include('partials.country')
                        </select>
                        <i class="las la-flag input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="mobile">@lang("Mobile")</label>
                        <input type="text" name="mobile" class="form-control b-radius--capsule" id="mobile" value="{{ old('mobile') }}" placeholder="@lang('Enter Mobile Number')">
                        <i class="las la-mobile input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="password">@lang("Password")</label>
                        <input type="password" name="password" id="password" class="form-control b-radius--capsule" placeholder="@lang("Enter Password")">
                        <i class="las la-key input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">@lang("Confirm Password")</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control b-radius--capsule" placeholder="@lang("Retype Password")">
                        <i class="las la-key input-icon"></i>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25 b-radius--capsule">
                            @lang("Register")<i class="las la-sign-in-alt"></i>
                        </button>
                    </div>
                    <p class="text-center">
                        @lang("Already have an account?") <a href="{{ route('owner.login') }}">@lang("Sign In Now")</a>
                    </p>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        border-radius: 20px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered[title=" Select Country"] {
        color: #c9c9c9;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered:not([title=" Select Country"]) {
        color: #495057;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 20px;
    }
</style>
@endpush
