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
                                    <th>@lang('Starts From')</th>
                                    <th>@lang('Ends At')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($schedules as $schedule)
                                @php
                                    $date   = Carbon\Carbon::parse($schedule->starts_from);
                                    $now    = Carbon\Carbon::parse($schedule->ends_at);
                                    $diff   = $date->diff($now);
                                @endphp
                            <tr>
                                <td data-label="@lang('S.N.')">{{$schedule ->current_page-1 * $schedule ->per_page + $loop->iteration }}</td>
                               <td data-label="@lang('Starts From')">
                                    {{ showDateTime($schedule->starts_from, 'h:i a') }}
                                </td>
                                <td data-label="@lang('Ends At')">{{ showDateTime($schedule->ends_at, 'h:i a') }}</td>

                                <td data-label="@lang('Duration')">{{ $diff->format('%h Hours %i minutes') }}</td>

                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-id="{{ $schedule->id }}" data-starts_from="{{ showDateTime($schedule->starts_from, 'H:i') }}" data-ends_at="{{ showDateTime($schedule->ends_at, 'H:i') }}" class="icon-btn edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                        <i class="la la-pencil"></i>
                                    </a>

                                    <a href="javascript:void(0)" data-id="{{ $schedule->id }}" class="ml-1 icon-btn btn--danger delete-btn {{ $schedule->status==0?'disabled':'' }}" data-toggle="tooltip" data-placement="top" data-action_type="delete" title="@lang('Delete')">
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
                <div class="card-footer py-4">
                    {{ $schedules->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Schedule')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('co-owner.trip.schedule.store', 0) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>@lang('Starts From') <span class="text-danger">*</span></label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control" placeholder="@lang('--:--')" name="starts_from" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Ends At')<span class="text-danger">*</span></label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control" placeholder="@lang('--:--')" name="ends_at" autocomplete="off">
                            </div>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Add Schedule')</button>
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
                    <h5 class="modal-title">@lang('Update Supervisor')</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Starts From') <span class="text-danger">*</span></label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control" placeholder="@lang('--:--')" name="starts_from" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Ends At')<span class="text-danger">*</span></label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control" placeholder="@lang('--:--')" name="ends_at" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group ">
                            <button type="submit" class="btn btn-block btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Removal Modal -->
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
                        @lang('Are you sure to delete this Schedule?')
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
    @if(request()->routeIs('co-owner.trip.schedule'))
    <button data-toggle="modal" data-target="#addModal" class="btn btn--success btn-sm box--shadow1 text-small">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
    @else
        <a href="{{route('co-owner.trip.schedule.index')}}" class="btn btn-dark"><i class="la la-reply"></i> @lang('Back')</a>
    @endif
    @if(request()->routeIs('co-owner.trip.schedule.index'))
        <a href="{{ route('co-owner.trip.schedule.trashed') }}" class="btn btn-danger d-block"><i class="fas fa-trash-alt"></i>@lang('Trashed')</a>
    @endif
@endpush

@push('script-lib')
<script src="{{asset('assets/all_vendors/js/vendor/bootstrap-clockpicker.min.js')}}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('click', '.edit-btn', function () {
                var modal       = $('#editModal');
                var id        = $(this).data('id');
                var starts_from = $(this).data('starts_from');
                var ends_at     = $(this).data('ends_at');

                var link  = `{{ route('co-owner.trip.schedule.store', '') }}/${id}`;
                modal.find('input[name=starts_from]').val(starts_from);
                modal.find('input[name=ends_at]').val(ends_at);

                modal.find('form').attr('action', link);
                modal.modal('show');
            });

            $(document).on('click', '.delete-btn', function () {
                var modal = $('#deleteModal');
                var id  = $(this).data('id');
                modal.find('.action-type').text($(this).data('action_type'));
                var link  = `{{ route('co-owner.trip.schedule.remove', '') }}/${id}`;
                modal.find('form').attr('action', link);
                modal.modal('show');
            });


            // clock picker
            $('.clockpicker').clockpicker({
                placement: 'bottom',
                align: 'left',
                donetext: 'Done',
                autoclose:true,
            });

            $(document).keypress(function(e) {
                if(e.charCode == 103) {
                    // Your Code
                }
            });
        })(jQuery)

    </script>
@endpush


