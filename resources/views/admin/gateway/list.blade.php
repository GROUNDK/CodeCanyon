@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card ">
                <div class="card-body ">

                    <div class="table-responsive table-responsive--sm">
                        <table class="default-data-table table ">
                            <thead>
                            <tr>
                                <th>@lang('Gateway')</th>
                                <th>@lang('Supported Currency')</th>
                                <th>@lang('Enabled Currency')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($gateways as $k=>$gateway)
                                <tr>
                                    <td data-label="@lang('Gateway')">
                                        <div class="user">
                                            <div class="thumb">
                                                <a href="{{ getImage(imagePath()['gateway']['path'].'/'. $gateway->image)}}" class="image-popup">
                                                    <img src="{{ getImage(imagePath()['gateway']['path'].'/'. $gateway->image)}}" alt="image">
                                                </a>
                                            </div>
                                            <span class="name">{{$gateway->name}}</span>
                                        </div>
                                    </td>


                                    <td data-label="@lang('Supported Currency')">
                                        {{ count(json_decode($gateway->supported_currencies,true)) }}
                                    </td>
                                    <td data-label="@lang('Enabled Currency')">
                                        {{ $gateway->currencies->count() }}
                                    </td>


                                    <td data-label="@lang('Status')">
                                        @if($gateway->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Disabled')</span>
                                        @endif

                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.deposit.gateway.edit', $gateway->alias) }}"
                                            class="icon-btn editGatewayBtn" data-toggle="tooltip" title="" data-original-title="@lang('Edit')">
                                            <i class="la la-pencil"></i>
                                        </a>


                                        @if($gateway->status == 0)
                                            <button data-toggle="modal" data-target="#activateModal"
                                                    class="icon-btn bg--success ml-1 activateBtn"
                                                    data-code="{{$gateway->code}}"
                                                    data-name="{{$gateway->name}}" data-original-title="Enable">
                                                <i class="la la-eye"></i>
                                            </button>
                                        @else
                                            <button data-toggle="modal" data-target="#deactivateModal"
                                               class="icon-btn bg--danger ml-1 deactivateBtn"
                                               data-code="{{$gateway->code}}"
                                               data-name="{{$gateway->name}}" data-original-title="Disable">
                                                <i class="la la-eye-slash"></i>
                                            </button>
                                        @endif
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



    {{-- ACTIVATE METHOD MODAL --}}
    <div id="activateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Payment Method Activation Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.deposit.gateway.activate')}}" method="POST">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Are you sure to activate') <span class="font-weight-bold method-name"></span> @lang('method?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>

                        <button type="submit" class="btn btn--primary">@lang('Activate')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DEACTIVATE METHOD MODAL --}}
    <div id="deactivateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Payment Method Disable Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.deposit.gateway.deactivate')}}" method="POST">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Are you sure to disable') <span class="font-weight-bold method-name"></span> @lang('method?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Disable')</button>
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
            $('.image-popup').magnificPopup({
                type: 'image'
            });

            $('.activateBtn').on('click', function () {
                var modal = $('#activateModal');
                modal.find('.method-name').text($(this).data('name'));
                modal.find('input[name=code]').val($(this).data('code'));
            });

            $('.deactivateBtn').on('click', function () {
                var modal = $('#deactivateModal');
                modal.find('.method-name').text($(this).data('name'));
                modal.find('input[name=code]').val($(this).data('code'));
            });
        })(jQuery)
    </script>
@endpush
