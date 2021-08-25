@extends('owner.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                    <h6 class="card-title mt-0">
                        @if($my_ticket->status == 0)
                            <span class="text--small font-weight-normal badge--success">@lang('Open')</span>
                        @elseif($my_ticket->status == 1)
                            <span class="text--small font-weight-normal badge--primary">@lang('Answered')</span>
                        @elseif($my_ticket->status == 2)
                            <span class="text--small font-weight-normal badge--warning">@lang('Replied')</span>
                        @elseif($my_ticket->status == 3)
                            <span class="text--small font-weight-normal badge--dark">@lang('Closed')</span>
                        @endif
                        [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                    </h6>

                    <button class="btn btn--dark close-button" type="button" data-toggle="modal" data-target="#DelModal"><i class="las la-times"></i>@lang('Close Ticket')</button>
                </div>

                <div class="card-body">
                    @if($my_ticket->status != 4)
                        <form method="post" action="{{ route('owner.ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form-control-lg" id="inputMessage" placeholder="@lang('Your Reply...')" rows="4" cols="10"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="inputAttachments">@lang('Attachments')</label>

                                        <div class="file-upload-wrapper" data-text="@lang('Select your file!')">
                                            <input type="file" name="attachments[]" id="inputAttachments"
                                            class="file-upload-field"/>
                                        </div>
                                        <div id="fileUploadsContainer"></div>

                                        <div class="ticket-attachments-message text-muted text--small my-2">
                                            @lang('Allowed File Extensions: .jpg, .jpeg, .png, .pdf')
                                        </div>
                                    </div>


                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="btn btn--primary add-more">
                                            <i class="la la-plus"></i> @lang('Add More')
                                        </a>
                                    </div>
                                    <button type="submit" class="btn btn--success float-right" name="replayTicket" value="1">
                                        <i class="la la-reply"></i> @lang('Reply')
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('All Messages')</h5>
                </div>
                <div class="card-body">
                    @foreach($messages as $message)
                        @if($message->admin_id == 0)
                            <div class="single-answer from__client box--shadow1 mt-30">
                                <div class="single-answer__header bg--dark">
                                    <div class="left">

                                        <h5 class="text-white rounded">
                                            {{ $message->ticket->name }}
                                        </h5>
                                    </div>
                                    <div class="right"> <span class="text--small text-white-50" title="{{ showDateTime($message->created_at) }}">{{ diffForHumans($message->created_at) }} </span> </div>
                                </div>
                                <div class="single-answer__body">
                                    <p>{{$message->message}}</p>
                                    @if($message->attachments()->count() > 0)
                                        <div class="mt-2">
                                            @foreach($message->attachments as $image)
                                                <a href="{{route('owner.ticket.download',encrypt($image->id))}}" class="mr-3">
                                                    <i class="fa fa-file"></i> @lang('Attachment') {{$loop->iteration}}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                        <div class="single-answer from__admin box--shadow1 mt-30">
                            <div class="single-answer__header bg--primary">
                                <div class="left">
                                    <h5 class="text--white">
                                        {{ $message->admin->name }}
                                    </h5>
                                </div>
                                <div class="right"> <span class="text--small text-white-50" title="{{ showDateTime($message->created_at) }}">{{ diffForHumans($message->created_at) }} </span> </div>

                            </div>

                            <div class="single-answer__body">
                                <p>{{$message->message}}</p>

                                @if($message->attachments()->count() > 0)
                                    <div class="mt-2">
                                        @foreach($message->attachments as $k=> $image)
                                            <a href="{{route('owner.ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i> @lang('Attachment') {{++$k}} </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        @endif

                    @endforeach
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post" action="{{ route('owner.ticket.reply', $my_ticket->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Confirmation Alert!')</h5>

                        <button type="button" class="close close-button" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <strong class="text-dark">@lang('Are you sure you want to Close This Support Ticket?')</strong>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn--dark btn-sm" data-dismiss="modal">@lang('No')</button>

                        <button type="submit" class="btn btn--success btn-sm" name="replayTicket" value="2">@lang('Yes')</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        'use strict';
        (function($){
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
            $('.add-more').on('click',function () {
                $("#fileUploadsContainer").append(`
                <div class="file-upload-wrapper" data-text="@lang('Select your file!')"><input type="file" name="attachments[]" id="inputAttachments" class="file-upload-field"/></div>`)
            });
        })(jQuery)
    </script>
@endpush
