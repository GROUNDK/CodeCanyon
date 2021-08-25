@extends('co-owner.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--md table-responsive">
                        <table class="default-data-table table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('City')</th>
                                    <th>@lang('Counter Manager')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($counters as $counter)
                            <tr>
                                <td data-label="@lang('S.N.')">{{$counter ->current_page-1 * $counter ->per_page + $loop->iteration }}</td>

                                <td data-label="@lang('Name')" data-toggle="tooltip" data-placement="top" title="{{ __($counter->location) }}">
                                    {{$counter->name}}
                                </td>
                                <td data-label="@lang('City')">{{$counter->city}}</td>
                                <td data-label="@lang('Counter Manager')">{{ optional($counter->counterManager)->name}}</td>
                                <td data-label="@lang('Mobile')">{{ $counter->mobile }}</td>
                                <td data-label="@lang('Status')">
                                    <span class="text--small badge font-weight-normal badge--{{$counter->status?'success':'danger'}}">
                                        {{$counter->status?trans('Active'):trans('Inactive')}}
                                    </span>
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-counter="{{ $counter }}" class="icon-btn edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>
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
            </div><!-- card end -->
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Counter')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('co-owner.counter.store', 0) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="form-group">
                            <label for="city">@lang('City')</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="@lang('Type Here...')"/>
                        </div>

                        <div class="form-group">
                            <label for="mobile">@lang('Mobile')</label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="form-group">
                            <label for="counter_manager">@lang('Counter Manager')</label>
                            <select class="select2-basic" name="counter_manager" id="counter_manager" >
                                <option value="" selected>@lang('Select One')</option>
                                @foreach ($counter_managers as $counter_manager)
                                    @if($counter_manager->counter == null)
                                        <option value="{{$counter_manager->id}}">{{$counter_manager->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="location">@lang('Location')</label>
                            <textarea class="form-control" name="location" id="location" rows="3" placeholder="@lang('Type Here...')"></textarea>
                        </div>

                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Add Counter')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Counter')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="edit-name">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit-name" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="form-group">
                            <label for="city">@lang('City')</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="@lang('Type Here...')"/>
                        </div>

                        <div class="form-group">
                            <label for="mobile">@lang('Mobile')</label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="@lang('Type Here...')" required/>
                        </div>

                        <div class="form-group">
                            <label for="edit-counter_manager">@lang('Counter Manager')</label>
                            <select class="select2-basic" name="counter_manager" id="edit-counter_manager">
                                <option value="" selected>@lang('Select One')</option>
                                @foreach ($counter_managers as $counter_manager)
                                <option value="{{$counter_manager->id}}">{{$counter_manager->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="location">@lang('Location')</label>
                            <textarea class="form-control" name="location" id="location" rows="3" placeholder="@lang('Type Here...')"></textarea>
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

@endsection

@push('breadcrumb-plugins')
    <button data-toggle="modal" data-target="#addModal" class="btn btn-sm btn--success text--small box--shadow1">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('click', '.edit-btn', function () {
                var modal = $('#editModal');
                var data  = $(this).data('counter');
                var link  = `{{ route('co-owner.counter.store', '') }}/${data.id}`;
                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=mobile]').val(data.mobile);
                modal.find('input[name=city]').val(data.city);
                modal.find('textarea[name=location]').val(data.location);

                if(data.counter_manager_id != null)
                modal.find('select[name=counter_manager]').html(`<option value="">@lang('Select One')</option><option value="${data.counter_manager_id}">${data.counter_manager.name}</option>`);

                modal.find('select[name=counter_manager]').val(data.counter_manager_id);

                modal.find('.select2-basic').select2({
                    dropdownParent: $('#editModal')
                });

                if(data.status == 0){
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',false);

                }else{
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',true);
                }

                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $('#editModal').on('hidden.bs.modal', event => {
                $('#editModal').find('select[name=counter_manager]').html();
            });

        })(jQuery)
    </script>
@endpush
