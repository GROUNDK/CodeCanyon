@extends('layouts.master')

@section('content')

    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/all_vendors/images/1.jpg')}}">
            <div class="form-wrapper">

                    <div class="logo-text mb-15">
                        <h5 class="title text-center">@lang('Please verify your email to get access')</h5>
                        <p class="text-center">@lang('Your Email:') <strong>{{auth()->guard('owner')->user()->email}}</strong></p>
                    </div>

                    <form class="cmn-form mt-30" method="post" action="{{route('owner.verify_email')}}">
                        @csrf
                        <div class="form-group">
                            <div id="phoneInput">
                                <div class="phone text-center">
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                    <input type="text" name="email_verified_code[]" class="letter"
                                            pattern="[0-9]*" inputmode="numeric" maxlength="1" required>
                                </div>
                            </div>
                        </div>

                        <p class="mt-3 ">@lang('Please check including your Junk/Spam Folder. if not found, you can')
                            <a class="text-danger"
                                href="{{route('owner.send_verify_code')}}?type=email">@lang('Resend code')</a>
                        </p>
                        <div class="form-group">
                            <input type="submit" class="submit-btn mt-25 b-radius--capsule" value="@lang('Submit')">
                        </div>
                    </form>
            </div>
        </div><!-- login-area end -->
    </div>


@endsection



@push('script-lib')
    <script src="{{asset('assets/all_vendors/js/jquery.inputLettering.js')}}"></script>
@endpush

@push('style')
<style>
    #phoneInput .field-wrapper {
        position: relative;
        text-align: center;
    }

    #phoneInput .form-group {
        min-width: 300px;
        width: 50%;
        margin: 4em auto;
        display: flex;
        border: 1px solid rgba(96, 100, 104, 0.3);
    }

    #phoneInput .letter {
        height: 50px;
        border-radius: 0;
        text-align: center;
        max-width: calc((100% / 10) - 1px);
        flex-grow: 1;
        flex-shrink: 1;
        flex-basis: calc(100% / 10);
        outline-style: none;
        padding: 5px 0;
        font-size: 18px;
        font-weight: bold;
        color: red;
        border: 1px solid #0e0d35;
    }

    #phoneInput .letter + .letter {
    }

    @media (max-width: 480px) {
        #phoneInput .field-wrapper {
            width: 100%;
        }

        #phoneInput .letter {
            font-size: 16px;
            padding: 2px 0;
            height: 35px;
        }
    }

</style>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('#phoneInput').letteringInput({
                inputClass: 'letter'
            });
        })(jQuery)
    </script>
@endpush
