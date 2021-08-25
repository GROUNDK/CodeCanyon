@extends('supervisor.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">

                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage('assets/owner/images/supervisor/'. @$supervisor->image, true)}}" alt="profile-image">
                        </div>
                        <div class="pl-3">
                            <h4 class="text--white">{{@$supervisor->owner_name}}</h4>
                        </div>
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Owner Name')
                            <span class="font-weight-bold">{{@$supervisor->owner_name}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="font-weight-bold">{{@$supervisor->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="font-weight-bold">{{@$supervisor->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile')
                            <span  class="font-weight-bold">{{@$supervisor->mobile}}</span>
                        </li>
                        @if(@$supervisor->address->address != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address')
                            <span  class="font-weight-bold">{{@$supervisor->address->address}}</span>
                        </li>
                        @endif
                        @if(@$supervisor->address->state != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            <span  class="font-weight-bold">{{@$supervisor->address->state}}</span>
                        </li>
                        @endif
                        @if(@$supervisor->address->zip != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Zip')
                            <span  class="font-weight-bold">{{@$supervisor->address->zip}}</span>
                        </li>
                        @endif
                        @if(@$supervisor->address->city != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span  class="font-weight-bold">{{@$supervisor->address->city}}</span>
                        </li>
                        @endif
                        @if(@$supervisor->address->country != '')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            <span  class="font-weight-bold">{{@$supervisor->address->country}}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>
                    <form action="{{ route('supervisor.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage('assets/owner/images/supervisor/'. $supervisor->image, true) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--success">@lang('Upload Profile Image')</label>
                                                <small class="mt-2 text--danger">@lang('Supported Files:') @lang('jpeg, jpg') @lang('Image will be resized into 400x400px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold" for="name">@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ $supervisor->name }}" required>
                                </div>

                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold" for="username">@lang('Username')</label>
                                    <input class="form-control" type="text" name="username" id="username" value="{{ $supervisor->username }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold" for="email">@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" id="email" value="{{ $supervisor->email }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold" for="mobile">@lang('Mobile')</label>
                                    <input class="form-control" type="text" name="mobile" id="mobile" value="{{ $supervisor->mobile }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold" for="country">@lang('Country')</label>
                                    <select class="form-control" id="country" name="country" class="mb-3">
                                        @include('partials.country')
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold" for="state">@lang('State')</label>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="@lang('State')" value="{{@$supervisor->address->state}}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold" for="zip">@lang('Zip Code')</label>
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="@lang('Zip Code')" value="{{@$supervisor->address->zip}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city" class="form-control-label font-weight-bold">@lang('City')</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="@lang('City')" value="{{@$supervisor->address->city}}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="form-control-label font-weight-bold">@lang('Address:')</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="@lang('Address')" required>{{@$supervisor->address->address}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('supervisor.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('select[name=country]').val("{{  @$supervisor->address->country }}");
        })(jQuery)
    </script>
@endpush
