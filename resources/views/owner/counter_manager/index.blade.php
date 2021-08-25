@extends('owner.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--md  table-responsive">
                        <table class="default-data-table table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Counter')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($counter_managers as $counter_manager)
                            <tr>
                                <td data-label="@lang('S.N.')">{{$counter_manager ->current_page-1 * $counter_manager ->per_page + $loop->iteration }}</td>
                               <td data-label="@lang('Name')">
                                    <div class="user">
                                        <div class="thumb">
                                            <a href="{{ getImage('assets/owner/images/counterManager/'. $counter_manager->image, true)}}" class="image-popup">
                                                <img src="{{ getImage('assets/owner/images/counterManager/'. $counter_manager->image, true)}}" alt="image">
                                            </a>
                                            <span class="name">{{$counter_manager->name}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="@lang('Username')">{{ $counter_manager->username }}</td>

                                <td data-label="@lang('Email')">{{ $counter_manager->email }}</td>
                                <td data-label="@lang('Counter')">{{ optional($counter_manager->counter)->name }}</td>

                                <td data-label="@lang('Status')">
                                <span class="text--small badge font-weight-normal badge--{{$counter_manager->status?'success':'danger'}}">
                                        {{$counter_manager->status?trans('Active'):trans('Inactive')}}
                                    </span>
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-counter_manager="{{ $counter_manager }}" class="icon-btn edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $counter_manager->id }}" class="ml-1 icon-btn btn--danger delete-btn" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
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

@if(request()->routeIs('owner.counter_manager.index'))
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Counter Manager')
                    </h5>
                    <div class="text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <a href="{{route('owner.counter_manager.create')}}" class="close" data-toggle="tooltip" title="@lang('Open in New Page')"><span aria-hidden="true">&#10064;</span></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('owner.counter_manager.store', 0) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">@lang('Name')<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="form-group">
                            <label for="username">@lang('Username')<span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="@lang('Type Here...')" autocomplete="off"  required/>
                            <small class="text-danger">@lang('Username must not be less than 6 character')</small>
                        </div>
                        <div class="form-group">
                            <label for="email">@lang('Email') <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>
                        <div class="form-group">
                            <label for="mobile">@lang('Mobile')</label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="@lang('Type Here...')"/>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                @lang('Password') <span class="text-danger">*</span>
                                <span class="text-warning ml-2"><i class="fa fa-info-circle"></i> @lang('Default Password Is 123456') </span>
                            </label>
                            <input type="password" name="password" id="password" value="123456" class="form-control" placeholder="******" required/>
                            <small class="text-danger">@lang("Password must not be less than 6 character")</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">@lang('Confirm Password')<span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" value="123456" id="password_confirmation" class="form-control" placeholder="******" required/>
                        </div>

                        <div class="form-group">
                            <label for="address">@lang('Address')</label>
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="@lang('Type Address Here')"></textarea>
                        </div>

                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Add Counter Manager')</button>
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
                    <h5 class="modal-title">@lang('Update Counter Manager')</h5>
                    <div class="text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <a href="" class="close" data-toggle="tooltip" title="@lang('Open in New Page')"><span aria-hidden="true">&#10064;</span></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="edit-name">@lang('Name')<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit-name" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>
                        <div class="form-group">
                            <label for="edit-username">@lang('Username')<span class="text-danger">*</span></label>
                            <input type="text" name="username" id="edit-username" class="form-control" placeholder="@lang('Type Here...')" autocomplete="off"  required/>
                            <small class="text-danger">@lang('Username must not be less than 6 character')</small>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">@lang('Email')<span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit-email" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>
                        <div class="form-group">
                            <label for="edit-mobile">@lang('Mobile')</label>
                            <input type="text" name="mobile" id="edit-mobile" class="form-control" placeholder="@lang('Type Here...')"/>
                        </div>

                        <div class="form-group">
                        <label for="edit-address">@lang('Address')</label>
                        <textarea class="form-control" name="address" id="edit-address" rows="3" placeholder="Type Address Here"></textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                        </div>
                        <div class="form-group ">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    @endif
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
                        @lang('Are you sure to delete this Counter Manager?')
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
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('.image-popup').magnificPopup({
                type: 'image'
            });

            $(document).on('click', '.edit-btn', function () {
                var modal = $('#editModal');
                var data  = $(this).data('counter_manager');
                var link  = `{{ route('owner.counter_manager.store', '') }}/${data.id}`;
                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=username]').val(data.username);
                modal.find('input[name=email]').val(data.email);
                modal.find('input[name=mobile]').val(data.mobile);

                if(data.address != null)
                modal.find('textarea[name=address]').text(data.address.address);
                else
                modal.find('textarea[name=address]').text('');

                if(data.status == 0){
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',false);

                }else{
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',true);
                }
                var elink = `{{route('owner.counter_manager.edit','')}}/${data.id}`

                modal.find('.close').attr('href', elink);
                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id  = $(this).data('id');
                var link  = `{{ route('owner.counter_manager.remove', '') }}/${id}`;

                modal.find('form').attr('action', link);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush


