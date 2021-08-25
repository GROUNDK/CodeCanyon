@extends('owner.layouts.app')

@section('panel')


<div class="row mb-none-30 justify-content-center">

    @forelse($packages as $package)
        <div class="col-lg-4 col-md-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="pricing-table text-center">

                        <h4 class="package-name b-radius--capsule bg--10 mb-20 p-2">{{ $package->package->name }}</h4>
                            <span class="price">
                                {{ $general->cur_sym }}{{ $package->package->price }}
                            </span>
                            <small>

                                / {{ $package->package->time_limit }} {{ getPackageLimitUnit($package->package->unit) }}
                            </small>
                        <p class="font-weight-bold text--danger"> @lang('Expires On'): {{ showDateTime($package->ends_at, 'F d, Y') }}</p>

                        <ul class="package-features-list mt-3">
                            @foreach ($features as $item)
                                <li><i class="fas fa-check-circle text--success"></i> @lang($item->name) </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-5">
                <h6 class="text-center text--danger">{{ __($empty_message) }}</h6>
            </div>
        </div>
    </div>
    @endforelse

</div>

<!-- Modal -->
<div class="modal fade" id="buyPackModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">@lang('Buy Package Preview')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body p-0">

                <ul class="list-group d-flex">
                    <li class="list-group-item d-flex justify-content-between align-items-center rounded-0">
                        <strong>@lang('Name') </strong> <span class="package-name"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center rounded-0">
                        <strong>@lang('Price')</strong> <span class="package-price"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center rounded-0">
                        <strong>@lang('Expires On')</strong> <span class="package-expires"></span>
                    </li>
                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Cancel')</button>
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <button type="submit" class="btn btn--success btn-block">@lang('Buy')</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')

    <script>
        "use strict";
        (function($){
            $(document).on('click', '.buyPack', function (event){
                var modal   = $('#buyPackModal');
                var pack    = $(this).data('package');
                var expires = $(this).data('expires');

                modal.find('input[name=id]').val(pack.id);
                modal.find('.package-name').text(pack.name);
                modal.find('.package-price').text(pack.price+"{{ $general->cur_sym }}");
                modal.find('.package-expires').text(expires);
                modal.modal('show');
            });
        })(jQuery);

    </script>

@endpush
