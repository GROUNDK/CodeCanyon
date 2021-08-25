@extends('co-owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Layout')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($seat_layouts as $seat_layout)
                            <tr>
                                <td data-label="@lang('S.N.')">{{$loop->iteration }}</td>

                                <td data-label="@lang('Layout')">{{ $seat_layout->layout }}</td>

                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-id="{{ $seat_layout->id }}" data-layout="{{$seat_layout->layout}}" class="ml-1 icon-btn btn--primary edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $seat_layout->id }}" class="ml-1 icon-btn btn--danger delete-btn" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                                        <i class="la la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>

    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Layout')
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('co-owner.fleet_manage.seat_layout.add', 0) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="layout">@lang('Layout') <span class="text-danger">*</span></label>
                            <input type="text" name="layout" id="layout" class="form-control integer-validation" placeholder="@lang('2 x 3')" required/>
                            <small class="text--warning"><i class="la la-info-circle"></i> @lang('Just type left and right value, a seperator (x) will be added automatically')</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Add Layout')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Driver')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="layout">@lang('Layout') <span class="text-danger">*</span></label>
                            <input type="text" name="layout" id="layout" class="form-control integer-validation" placeholder="@lang('2 x 3')" required/>
                            <small class="text--warning"><i class="la la-info-circle"></i> @lang('Just type left and right value, a seperator (x) will be added automatically')</small>
                        </div>

                        <div class="form-group ">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                       @lang('Are you sure to delete this seat layout?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button data-toggle="modal" data-target="#addModal" class="btn btn-sm btn--success text--small box--shadow1">
        <i class="fas fa-plus"></i>@lang('Add New')
    </button>
</div>
@endpush

@push('script')
    <script>

        'use strict';
        (function($){

            $(document).on('click', '.edit-btn', function () {
                var modal = $('#editModal');
                var link  = `{{ route('co-owner.fleet_manage.seat_layout.add', '') }}/${$(this).data('id')}`;

                modal.find('input[name=layout]').val($(this).data('layout'));

                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('keypress', 'input[name=layout]', function(e){
                var layout = $(this).val();
                if(layout != ''){
                    if(layout.length > 0 && layout.length <= 1)
                    $(this).val(`${layout} x `);

                    if(layout.length > 4) {
                        return false;
                    }
                }
            });

            $(document).on('keyup', 'input[name=layout]', function(e){
                var key = event.keyCode || event.charCode;
                if( key == 8 || key == 46 ){
                    console.log($(this).val());
                    $(this).val($(this).val().replace(' x ',''));
                }

            });

            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id  = $(this).data('id');
                var link  = `{{ route('co-owner.fleet_manage.seat_layout.remove', '') }}/${id}`;
                modal.find('form').attr('action', link);
                modal.modal('show');
            });
        })(jQuery)



    </script>
@endpush


