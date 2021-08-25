@extends('layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/all_vendors/images/1.jpg')}}">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">@lang("Welcome to $general->sitename")</h4>
                <p> @lang("$page_title to $general->sitename dashboard")</p>
                <form action="{{ route('owner.loginSubmit') }}" method="POST" class="cmn-form mt-30">
                    @csrf
                    <div class="form-group">
                        <label>@lang("Username")</label>
                        <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" placeholder="@lang('Enter your username')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label>@lang("Password")</label>
                        <input type="password" name="password" class="form-control b-radius--capsule" placeholder="@lang('Enter your password')">
                        <i class="las la-key input-icon"></i>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">
                        <a href="{{ route('owner.password.reset') }}" class="text-muted text--small"><i class="las la-question-circle"></i>@lang("Forgot password?") </a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25 b-radius--capsule">
                            @lang('Login')<i class="las la-sign-in-alt"></i>
                        </button>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">

                        <a href="{{ route('owner.register') }}" class="text-muted text--small"><i class="las la-user-plus"></i> @lang('Not have an account?') <span class="text--danger">@lang('Create New')</span></a>
                    </div>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection

