@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.co-owner.store', $co_admin->id??0) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{$co_admin->name??null}}" class="form-control" placeholder="@lang('Type Here...')" required/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="username">@lang('Username')<span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="@lang('Type Here...')"/ value="{{$co_admin->username??null}}" required autocomplete="off">
                                    <small class="text-danger">@lang('Username must not be less than 6 character')</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="@lang('Type Here...')" value="{{$co_admin->email??null}}" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile">@lang('Mobile') <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" id="mobile" value="{{$co_admin->mobile??null}}" class="form-control" placeholder="@lang('Type Here...')" required/>
                                </div>
                            </div>

                            @if(!request()->routeIs('owner.co_admin.edit'))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">
                                        @lang('Password') <span class="text-danger">*</span>
                                        <span class="text-warning ml-2"><i class="fa fa-info-circle"></i> @lang('Default Password Is 123456')</span>
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control" value="123456" placeholder="******" required/>
                                    <small class="text-danger">@lang("Password must not be less than 6 character")</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">@lang('Confirm Password')<span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" value="123456" id="password_confirmation" class="form-control" placeholder="******" required/>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12">
                                <div class="form-group">
                                <label for="address">@lang('Address')</label>
                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="@lang('Type Address Here')">{{$co_admin->address->address??null}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" @if(isset($co_admin))@if($co_admin->status == 1) checked @endif @else checked @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@if(!isset($co_admin)) @lang('Add CoOwner') @else @lang('Save Changes') @endif</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
