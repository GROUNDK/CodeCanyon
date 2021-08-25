@extends('owner.layouts.app')

@section('panel')
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-deposit ">

                    <div class="card-body  ">
                        <form action="{{ route('owner.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <h4 class="text-center">@lang('You\'re requested to pay the final amount of ') {{ getAmount($data['final_amo']) }} {{ $method->currency }} @lang('including') {{ getAmount($data['charge']*$data['rate']) }} {{ $method->currency }} @lang('for charge.')</h4>
                                    <hr>

                                    <h4 class="text-center bg--warning p-2 mb-2">@lang('Please Follow The Instruction')</h4>
                                    <p class="pt-2">@php echo $method->method->description @endphp</p>
                                    <hr>
                                </div>

                                @if($method->gateway_parameter)

                                    @foreach(json_decode($method->gateway_parameter) as $k => $v)

                                        @if($v->type == "text")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           name="{{$k}}"  value="{{old($k)}}" placeholder="{{__(ucwords($v->field_level))}}">
                                                </div>
                                            </div>
                                        @elseif($v->type == "textarea")
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><strong>{{inputTitle($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                        <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3">{{old($k)}}</textarea>

                                                    </div>
                                                </div>
                                        @elseif($v->type == "file")
                                        <div class="col-md-12">

                                            <label class="text-uppercase">
                                                <strong>
                                                    {{$v->field_level}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif
                                                </strong>
                                            </label>
                                            <div class="verification-img">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' name="{{$k}}" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                        <label for="imageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview" style="background-image: url({{ asset(imagePath()['image']['default']) }});">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--dark btn-block btn--capsule">@lang('Pay Now')</button>
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
        $("#imageUpload").on('change',function() {
            readURL(this);
        });
    })(jQuery)

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endpush
@push('style')
    <style>
        .verification-img .avatar-preview {
            border-radius: 0;
        }
    </style>
@endpush
