@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="type" value="element">
                        @if(@$data)
                            <input type="hidden" name="id" value="{{$data->id}}">
                        @endif

                        <div class="form-row">
                            @foreach($section->element as $k => $type)
                                @if($k != 'image_property')
                                    @if($type == 'image')

                                        <div class="col-md-4">
                                            <input type="hidden" name="has_image" value="1">
                                            <div class="form-group">
                                                <label>{{inputTitle($k)}}</label>
                                                <div class="image-upload">
                                                    <div class="thumb">
                                                        <div class="avatar-preview">
                                                            <div class="profilePicPreview" style="background-image: url({{ getImage('assets/images/frontend/' . $key .'/'. @$data->data_values->$k) }})">
                                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="avatar-edit">
                                                            <input type="file" class="profilePicUpload" name="image_input" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                            <label for="profilePicUpload1" class="bg--success">@lang('Image')</label>
                                                            <small class="mt-2 text-facebook">@lang('Supported Files:') <b>@lang('jpeg, jpg, png')</b>.
                                                                @if(@$section->element->image_property->size)
                                                                    | @lang('Will be resized to:') <b>{{ __($section->element->image_property->size) }}</b> @lang('px')
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            @push('divend')
                                        </div>
                                        @endpush

                                    @elseif($type == 'icon')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <div class="input-group has_append">
                                                    <input type="text" class="form-control" name="icon" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home" role="iconpicker"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    @elseif($type == 'textarea')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" required>{{ @$data->data_values->$k}}</textarea>
                                            </div>
                                        </div>

                                    @elseif($type == 'textarea-nic')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control nicEdit" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" >{{ @$data->data_values->$k}}</textarea>
                                            </div>
                                        </div>

                                    @elseif($type == 'number')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <input type="number" step="0.01" class="form-control" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" value="{{ @$data->data_values->$k }}" required/>
                                            </div>
                                        </div>
                                    @else

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{inputTitle($k)}}</label>
                                                <input type="text" class="form-control" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" value="{{ @$data->data_values->$k }}" required/>
                                            </div>
                                        </div>

                                    @endif
                                @endif
                            @endforeach
                            @stack('divend')
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



@push('breadcrumb-plugins')
    <a href="{{route('admin.frontend.sections',$key)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-backward"></i>@lang('Go Back')</a>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
    <link rel="stylesheet" href="{{ asset('assets/all_vendors/css/bootstrap-iconpicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/all_vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush

@push('script')
    <script>

        'use strict';
        (function($){
            $('.iconPicker').iconpicker({
                align: 'center', // Only in div tag
                arrowClass: 'btn-danger',
                arrowPrevIconClass: 'fas fa-angle-left',
                arrowNextIconClass: 'fas fa-angle-right',
                cols: 10,
                footer: true,
                header: true,
                icon: 'fas fa-bomb',
                iconset: 'fontawesome5',
                labelHeader: '{0} of {1} pages',
                labelFooter: '{0} - {1} of {2} icons',
                placement: 'bottom', // Only in button tag
                rows: 5,
                search: false,
                searchText: 'Search icon',
                selectedClass: 'btn-success',
                unselectedClass: ''
            }).on('change', function (e) {
                $(this).parent().siblings('input[name=icon]').val(`<i class="${e.icon}"></i>`);
            });
        })(jQuery)


    </script>
@endpush
