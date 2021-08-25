@extends('co-owner.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive--md  table-responsive">
                    <table class="default-data-table table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Number of Deck')</th>
                                <th>@lang('Seat Layout')</th>
                                <th>@lang('Total Seat')</th>
                                <th>@lang('AC / Non AC')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fleet_types as $fleet_type)
                                <tr>
                                    <td data-label="@lang('S.N.')">
                                        {{ $fleet_type ->current_page-1 * $fleet_type ->per_page + $loop->iteration }}
                                    </td>

                                    <td data-label="@lang('Name')">{{ $fleet_type->name }}</td>

                                    <td data-label="@lang('Number Of Deck')">{{ $fleet_type->deck }}</td>

                                    <td data-label="@lang('Seat Layout')">{{ $fleet_type->seat_layout }}</td>
                                    <td data-label="@lang('Total Seat')">{{ collect($fleet_type->seats)->sum() }}</td>

                                    <td data-label="@lang('AC / Non AC')">{{ $fleet_type->has_ac?'AC':'Non AC' }}</td>

                                    <td data-label="@lang('Status')">
                                        <span
                                            class="text--small badge font-weight-normal badge--{{ $fleet_type->status?'success':'danger' }}">
                                            {{ $fleet_type->status?trans('Active'):trans('Inactive') }}
                                        </span>
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" data-fleet_type="{{ $fleet_type }}" class="icon-btn edit-btn" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
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
                    </table><!-- table end -->
                </div>
            </div>

        </div><!-- card end -->
    </div>
</div>

<div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Fleet Type')
                </h5>
                <div class="text-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip"
                        title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <a href="{{ route('co-owner.fleet_manage.fleet_type.create') }}" class="close" data-toggle="tooltip" title="@lang('Open in New Page')"><span aria-hidden="true">&#10064;</span></a>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ route('co-owner.fleet_manage.fleet_type.store', 0) }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang('Name')<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="@lang('Classic / Vip / Royal')" required />
                    </div>

                    <div class="form-group">
                        <label for="seat_layout">@lang('Seat Layout')</label>
                        <select class="custom-select" name="seat_layout" id="seat_layout">
                            <option selected value="">@lang('Select One')</option>
                            @foreach($seat_layouts as $item)
                                <option value="{{ $item->layout }}">{{ $item->layout }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deck"> @lang('Number of Deck') <span class="text-danger">*</span></label>
                        <input type="text" name="number_of_deck" id="deck" class="form-control integer-validation" placeholder="@lang('1 / 2')" autocomplete="off" required/>
                    </div>

                    <div class="seat-number-wrapper">

                    </div>

                    <label class="d-block">@lang('Has Ac')</label>
                    <div class="radio-box-wrapper d-flex flex-wrap">
                        <div class="form-radio-box mr-3">
                            <input type="radio" id="has_ac_1" value="1" name="has_ac" >
                            <label for="has_ac_1">@lang('Yes')</label>
                        </div>
                        <div class="form-radio-box">
                            <input type="radio" id="has_ac_0" value="0" name="has_ac">
                            <label for="has_ac_0">@lang('No')</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn--primary">@lang('Add Fleet Type')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Fleet Type')</h5>
                <div class="text-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip"
                        title="@lang('Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <a href="" class="close" data-toggle="tooltip" title="@lang('Open in New Page')">
                        <span aria-hidden="true">&#10064;</span>
                    </a>
                </div>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang('Name')<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="@lang('Classic / Vip / Royal')" required />
                    </div>


                    <div class="form-group">
                        <label for="seat_layout">@lang('Seat Layout')</label>
                        <select class="custom-select" name="seat_layout" id="seat_layout">
                            <option selected value="">@lang('Select One')</option>
                            @foreach($seat_layouts as $item)
                            <option value="{{ $item->layout }}">{{ $item->layout }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deck">@lang('Number of Deck')<span class="text-danger">*</span></label>
                        <input type="text" name="number_of_deck" id="deck" class="form-control integer-validation" placeholder="@lang('1 / 2')" autocomplete="off" required />
                    </div>

                    <div class="seat-number-wrapper">

                    </div>

                    <label class="d-block">@lang('Has Ac')</label>
                    <div class="radio-box-wrapper d-flex flex-wrap">
                        <div class="form-radio-box mr-3">
                            <input type="radio" id="edit_has_ac_1" value="1" name="has_ac" >
                            <label for="edit_has_ac_1">@lang('Yes')</label>
                        </div>
                        <div class="form-radio-box">
                            <input type="radio" id="edit_has_ac_2" value="0" name="has_ac">
                            <label for="edit_has_ac_2">@lang('No')</label>
                        </div>
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


    <button data-toggle="modal" data-target="#addModal" class="btn btn-sm btn--success text--small box--shadow1"> <i class="fas fa-plus"></i>@lang('Add New')</button>



@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $(document).on('keyup', 'input[name=number_of_deck]', function(){
                var deckNumber = $(this).val();
                var i = 1;
                var fields =``;

                for (i; i <= deckNumber; i++ ){
                    fields +=`<div class="form-group">
                                <label for="seat[${i}]"> {{ trans('Seat Number for Deck') }} ${i} <span class="text-danger">*</span></label>
                                <input type="text" name="seats[${i}]" id="seat" class="form-control integer-validation" placeholder="{{ trans('100') }}" autocomplete="off" required/>
                            </div>`;
                }
                $('.seat-number-wrapper').html(fields);
            });


            $(document).on('click', '.edit-btn', function () {
                var modal   = $('#editModal');
                var data    = $(this).data('fleet_type');

                var link    = `{{ route('co-owner.fleet_manage.fleet_type.store', '') }}/${data.id}`;

                var deckNumber = data.deck;


                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=number_of_deck]').val(deckNumber);
                modal.find('input[name=total_seat]').val(data.total_seat);
                modal.find('select[name=seat_layout]').val(data.seat_layout);

                if(data.has_ac == 1){
                    modal.find(`input[name=has_ac][value="1"]`).prop("checked", true);
                }else if(data.has_ac == 0){
                    modal.find(`input[name=has_ac][value="0"]`).prop("checked", true);
                }

                if(data.status == 0){
                    modal.find('.toggle').addClass('btn--danger off').removeClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',false);

                }else{
                    modal.find('.toggle').removeClass('btn--danger off').addClass('btn--success');
                    modal.find('input[name="status"]').prop('checked',true);
                }
                var elink = `{{ route('co-owner.fleet_manage.fleet_type.edit','') }}/${data.id}`

                modal.find('.close').attr('href', elink);
                modal.find('form').attr('action', link);

                var i = 1;
                var fields =``;

                $.each(data.seats, function (i, val) {
                    fields +=`<div class="form-group">
                                <label for="seat[${i}]"> trans('Seat Number for Deck') }} ${i} <span class="text-danger">*</span></label>
                                <input type="text" name="seats[${i}]" value="${val}" id="seat" class="form-control integer-validation" placeholder="{{ trans('100') }}" autocomplete="off" required/>
                            </div>`;
                });



                $('.seat-number-wrapper').html(fields);
                modal.modal('show');
            });

            $('#editModal').on('hidden.bs.modal', function (e) {
                $('.seat-number-wrapper').html('');
            })

        })(jQuery)
    </script>
@endpush
