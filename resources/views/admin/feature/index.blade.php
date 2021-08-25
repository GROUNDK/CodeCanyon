@extends('admin.layouts.app')

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
                                <th>@lang('Name')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($features as $feature)
                            <tr>
                                <td data-label="@lang('S.N.')">{{$feature->first + $loop->iteration }}</td>
                                <td data-label="@lang('Name')">{{ $feature->name }}</td>

                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-id="{{ $feature->id }}" data-name="{{ $feature->name }}" class="icon-btn edit-btn mr-1" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <button type="button" class="icon-btn btn--danger delete-btn" data-id="{{ $feature->id }}" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                                        <i class="las la-trash"></i>
                                    </button>
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
                <div class="card-footer py-4">
                    <h5 class="text--warning">
                        <i class="fas fa-exclamation-circle"></i> @lang('All features will be applicable for any package')
                    </h5>
                    {{ $features->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>


    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Feature')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.feature.store', 0) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter Feature Name')" name="name" />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Add')</button>
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
                    <h5 class="modal-title">@lang('Edit Feature')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.feature.store', 0) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter Feature Name')" name="name" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE GATEWAY MODAL --}}
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete this feature?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--success" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--danger">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <button data-toggle="modal" data-target="#addModal" class="btn btn--success mr-1 mb-2 mb-xl-0">
    <i class="las la-plus"></i>@lang('Add New')</button>
    <form action="{{ route('admin.users.search', $scope ?? str_replace('admin.users.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or Email')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('click', '.edit-btn', function () {
                var modal = $('#editModal');
                var id    = $(this).data('id');
                var name  = $(this).data('name');
                var link  = `{{ route('admin.feature.store', '') }}/${id}`;
                modal.find('input[name=name]').val(name);

                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id  = $(this).data('id');
                var link  = `{{ route('admin.feature.remove', '') }}/${id}`;
                modal.find('form').attr('action', link);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush


