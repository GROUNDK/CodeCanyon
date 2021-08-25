@extends('owner.layouts.app')

@section('panel')
    <div class="row mb-none-30">

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-4">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">

                                            @php
                                                $logo = getImage(imagePath()['ownerLogo']['path'].'/'.$owner->username.'.png');
                                                $arr  = explode('/',$logo);
                                            @endphp


                                            <div class="profilePicPreview logoPicPrev" style="background-size: 100%;background-image: url({{ getImage(imagePath()['ownerLogo']['path'].'/'.$owner->username.'.png') }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>

                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" name="logo" value="$" @if(end($arr)=='default.png') required @endif>
                                            <label for="profilePicUpload1" class="bg--primary"><i class="la la-pencil-alt    "></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold" for="company_name">@lang('Company Name')</label>
                                    <input class="form-control" type="text" name="company_name" id="company_name" value="{{@$owner->general_settings->company_name}}" placeholder="@lang('Your Company Name')" required>
                                </div>

                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold" for="currency">@lang('Currency')</label>
                                    <input class="form-control" id="currency" type="text" name="currency" value="{{@$owner->general_settings->currency}}" placeholder="@lang('i.e. Dollar / Taka / Euro')" required>
                                </div>

                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold" for="currency_symbol">@lang('Currency Symbol') </label>
                                    <input class="form-control " id="currency_symbol" type="text" name="currency_symbol" value="{{@$owner->general_settings->currency_symbol}}" placeholder="@lang('i.e. $ / ৳ / €')" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .avatar-edit {
            position: relative;
            width: 100%;
        }

        .image-upload .thumb .avatar-edit label {
            border-radius: 25px;
            position: absolute !important;
            right: 0;
            bottom: 25px;
            width: 40px;
            height: 40px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
    </style>
@endpush
