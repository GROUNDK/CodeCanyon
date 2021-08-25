@extends('admin.layouts.app')

@section('panel')
    @if(@$section->content)
        <div class="row">
            <div class="col-lg-12 col-md-12 mb-30">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('admin.frontend.sections.content', $key)}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="content">
                            <div class="row">
                                @foreach($section->content as $k => $type)
                                    @if($k != 'image_property')
                                        @if($type == 'image')
                                            <div class="col-md-4">
                                                <input type="hidden" name="has_image" value="1">
                                                <div class="form-group">
                                                    <label>{{__(inputTitle($k))}}</label>
                                                    <div class="image-upload">
                                                        <div class="thumb">
                                                            <div class="avatar-preview">
                                                                <div class="profilePicPreview"

                                                                     style="background-image: url({{getImage('assets/images/frontend/' . $key .'/'. @$content->data_values->image) }})">
                                                                    <button type="button" class="remove-image"><i
                                                                            class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="avatar-edit">
                                                                <input type="file" class="profilePicUpload"
                                                                       name="image_input" id="profilePicUpload1"
                                                                       accept=".png, .jpg, .jpeg">
                                                                <label for="profilePicUpload1"
                                                                       class="bg--primary">@lang('Image')</label>
                                                                <small class="mt-2 text-facebook">@lang('Supported Files:') <b>@lang('jpeg,
                                                                        jpg, png')</b>.
                                                                    @if(@$section->content->image_property->size)
                                                                        | @lang('Will be resized to:')
                                                                        <b>{{$section->content->image_property->size}}</b>@lang('px')
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
                                                <div class="form-group ">
                                                    <label
                                                        class="form-control-label font-weight-bold">{{__(inputTitle($k))}}</label>
                                                    <div class="input-group has_append">
                                                        <input type="text" class="form-control" name="icon" required>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary iconPicker"
                                                                    data-icon="fas fa-home" role="iconpicker"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($type == 'textarea')

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label  font-weight-bold">{{__(inputTitle($k))}}</label>
                                                    <textarea rows="4" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required>{{ @$content->data_values->$k}}</textarea>
                                                </div>
                                            </div>

                                        @elseif($type == 'toggle')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label  font-weight-bold">{{__(inputTitle($k))}}</label>
                                                    <input name="show" type="checkbox" data-toggle="toggle"  class="toggle" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="Yes" data-off="No" {{ isset($content->data_values->show)?'checked':''}}>
                                                </div>
                                            </div>

                                        @elseif($type == 'textarea-nic')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label  font-weight-bold">{{__(inputTitle($k))}}</label>
                                                    <textarea rows="10" class="form-control nicEdit" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required>
                                                        {{ @$content->data_values->$k}}
                                                    </textarea>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label  font-weight-bold">{{__(inputTitle($k))}}</label>
                                                    <input type="text" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" value="{{@$content->data_values->$k }}" required/>
                                                </div>
                                            </div>

                                        @endif
                                    @endif
                                @endforeach
                                @stack('divend')
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn--dark btn-block btn-lg">@if(is_null($content)) @lang('Save') @else @lang('Update') @endif</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif






    @if(@$section->element)

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                    <div class="table-responsive table-responsive--lg">
                        <table class="default-data-table table ">
                            <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                @if(@$section->element->icon)
                                    <th>@lang('Icon')</th>
                                @endif
                                @if(@$section->element->image)
                                    <th>@lang('Image')</th>
                                @endif
                                @foreach($section->element as $k => $type)
                                    @if($type=='text' && $k !='modal')
                                        <th>{{__(inputTitle($k))}}</th>
                                    @endif
                                @endforeach
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse($elements as $data)
                                <tr>
                                    <td data-label="@lang('SL')">{{$loop->iteration}}</td>

                                    @if(@$section->element->icon)
                                        <td data-label="@lang('Icon')">@php echo $data->data_values->icon; @endphp</td>
                                    @endif
                                    @if(@$section->element->image)
                                        <td data-label="@lang('Image')">
                                            <div class="customer-details">
                                                <div class="thumb">
                                                    <a href="{{ getImage('assets/images/frontend/' . $key .'/'. @$data->data_values->image) }}" class="image-popup">
                                                        <img src="{{ getImage('assets/images/frontend/' . $key .'/'. @$data->data_values->image) }}" alt="image">
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    @foreach($section->element as $k => $type)
                                        @if($type=='text' && $k !='modal')
                                            <td data-label="{{__(inputTitle($k))}}">{{$data->data_values->$k}}</td>
                                        @endif
                                    @endforeach
                                    <td data-label="@lang('Action')">
                                        @if($section->element->modal)
                                            <button class="icon-btn  updateBtn" data-id="{{$data->id}}" data-all="{{json_encode($data->data_values)}}"><i class="la la-pencil-alt"></i></button>
                                        @else

                                            <a href="{{route('admin.frontend.sections.element',[$key,$data->id])}}" class="icon-btn  btn-primary"><i class="la la-pencil-alt"></i></a>
                                        @endif
                                        <button class="icon-btn btn--danger removeBtn" data-id="{{ $data->id }}"><i class="la la-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    </div>
                </div>
            </div>
        </div>




        {{-- Add METHOD MODAL --}}
        <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang ("Add New ". inputTitle($key). " Item")</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <div class="modal-body">
                            @foreach($section->element as $k => $type)
                                @if($k != 'image_property' && $k != 'modal')
                                    @if($type == 'icon')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" name="icon" required>

                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home" role="iconpicker"></button>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($type == 'image')

                                        <input type="hidden" name="has_image" value="1">
                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <div class="image-upload">
                                                <div class="thumb">
                                                    <div class="avatar-preview">
                                                        <div class="profilePicPreview" style="background-image: url({{ getImage('/') }})">
                                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type="file" class="profilePicUpload" name="image_input" id="profilePicUpload2" accept=".png, .jpg, .jpeg">
                                                        <label for="profilePicUpload2" class="bg--success">@lang('Image')</label>
                                                        <small class="mt-2 text-facebook">@lang('Supported Files:') <b>@lang('jpeg, jpg, png')</b>.
                                                            @if(@$section->element->image_property->size)
                                                                | @lang('Will be resized to:') <b>{{@$section->element->image_property->size}}</b> @lang('px')
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($type == 'textarea')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <textarea rows="4" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required></textarea>
                                        </div>

                                    @elseif($type == 'textarea-nic')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <textarea rows="4" class="form-control nicEdit" placeholder="{{__(inputTitle($k))}}" name="{{$k}}"></textarea>
                                        </div>

                                    @else

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <input type="text" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required/>
                                        </div>

                                    @endif
                                @endif
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--primary">@lang('Save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        {{-- Update METHOD MODAL --}}
        <div id="updateBtn" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang ("Update ". inputTitle($key). " Item") </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" class="edit-route" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <input type="hidden" name="id" value="">
                        <div class="modal-body">
                            @foreach($section->element as $k => $type)
                                @if($k != 'image_property' && $k != 'modal')
                                    @if($type == 'icon')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" name="icon" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home" role="iconpicker"></button>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($type == 'image')

                                        <input type="hidden" name="has_image" value="1">
                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <div class="image-upload">
                                                <div class="thumb">
                                                    <div class="avatar-preview">
                                                        <div class="profilePicPreview imageModalUpdate">
                                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type="file" class="profilePicUpload" name="image_input" id="profilePicUpload3" accept=".png, .jpg, .jpeg">
                                                        <label for="profilePicUpload3" class="bg--success"> @lang('Image')</label>
                                                        <small class="mt-2 text-facebook">@lang('Supported Files:') <b>@lang('jpeg, jpg, png')</b>.
                                                            @if(@$section->element->image_property->size)
                                                                | @lang('Will be resized to:') <b>{{@$section->element->image_property->size}}</b> @lang('px')
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($type == 'textarea')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <textarea rows="4" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required></textarea>
                                        </div>

                                    @elseif($type == 'textarea-nic')

                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <textarea rows="4" class="form-control nicEdit" placeholder="{{__(inputTitle($k))}}" name="{{$k}}"></textarea>
                                        </div>

                                    @else
                                        <div class="form-group">
                                            <label>{{__(inputTitle($k))}}</label>
                                            <input type="text" class="form-control" placeholder="{{__(inputTitle($k))}}" name="{{$k}}" required/>
                                        </div>

                                    @endif
                                @endif
                            @endforeach
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--primary">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- REMOVE METHOD MODAL --}}
        <div id="removeModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Confirmation')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.frontend.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <p class="font-weight-bold">@lang('Are you sure you want to delete this item?') @lang('Once deleted can not undo this action.')</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--danger">@lang('Remove')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('breadcrumb-plugins')
            @if($section->element->modal)
                <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
            @else
                <a href="{{route('admin.frontend.sections.element',$key)}}" class="btn btn-sm btn--success box--shadow1 text--small"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
            @endif
        @endpush
    @endif
    {{-- if section element end --}}


@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/all_vendors/css/bootstrap-iconpicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/all_vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush


@push('script')

    <script>
        'use strict';
        (function($){
            $('.image-popup').magnificPopup({
                type: 'image'
            });
            $('.removeBtn').on('click', function () {
                var modal = $('#removeModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });

            $('.addBtn').on('click', function () {
                var modal = $('#addModal');
                modal.modal('show');
            });

            $('.updateBtn').on('click', function () {
                var modal = $('#updateBtn');
                modal.find('input[name=id]').val($(this).data('id'));

                var obj = $(this).data('all');
                if (obj.image) {
                    var imgloc = '{{ asset('assets/images/frontend/') }}/{{$key}}/' + obj.image;
                    $(".imageModalUpdate").css("background-image", "url(" + imgloc + ")");
                }
                $.each(obj, function (index, value) {
                    modal.find('[name=' + index + ']').val(value);
                });

                modal.modal('show');
            });

            $('#updateBtn').on('shown.bs.modal', function (e) {
                $(document).off('focusin.modal');
            });

            $('#addModal').on('shown.bs.modal', function (e) {
                $(document).off('focusin.modal');
            });

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
