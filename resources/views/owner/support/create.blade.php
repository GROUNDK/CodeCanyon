@extends('owner.layouts.app')

@section('panel')
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card p-3">
                <div class="card-body">
                    <form  action="{{route('owner.ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">@lang('Name')</label>
                                <input type="text"  name="name" value="{{@$user->owner_name}}" class="form-control" placeholder="@lang('Enter Name')" required readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">@lang('Email address')</label>
                                <input type="email"  name="email" value="{{@$user->email}}" class="form-control" placeholder="@lang('Enter your Email')" required readonly>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="website">@lang('Subject')</label>
                                <input type="text" name="subject" value="{{old('subject')}}" class="form-control" placeholder="@lang('Subject')" >
                            </div>

                            <div class="col-12 form-group">
                                <label for="inputMessage">@lang('Message')</label>
                                <textarea name="message" id="inputMessage" rows="6" class="form-control">{{old('message')}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <span class="text-muted label--text">@lang('Attachments')</span>
                            <div class="custom-file">
                              <input type="file" name="attachments[]" accept=".jpg, .jpeg,.png" class="custom-file-input" id="inputAttachments">
                              <label class="custom-file-label" for="inputAttachments">@lang('Choose File')</label>
                            </div>

                            <div class="fileUploadsContainer"></div>
                            <p class="ticket-attachments-message text-muted">
                                @lang("Allowed File Extensions: .jpg, .jpeg, .png")
                            </p>
                        </div>


                        <div class="d-flex justify-content-between">
                            <a href="javascript:void(0)" class="btn btn--primary add-more">
                                <i class="la la-plus"></i> @lang('Add More')
                            </a>
                            <button class="btn btn--success" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<a href="{{route('owner.ticket') }}" class="btn btn-sm btn--primary float-right">
    @lang('My Support Ticket')
</a>
@endpush


@push('script')
    <script>
        'use strict';
        (function($){
            var i = 1;
            $('.add-more').on('click', function () {
                $(".fileUploadsContainer").append(`<div class="custom-file">
                                            <input type="file" name="attachments[]" class="custom-file-input" id="inputAttachments-${i}">
                                            <label class="custom-file-label" for="inputAttachments-${i}">@lang('Choose File')</label>
                                            </div>`);
            });

        })(jQuery)
    </script>
@endpush
