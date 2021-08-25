@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-responsive--sm">
                        <table class="default-data-table table ">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Subject')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($email_templates as $template)
                                <tr>
                                    <td data-label="@lang('Name')">{{ $template->name }}</td>
                                    <td data-label="@lang('Subject')">{{ $template->subj }}</td>
                                    <td data-label="@lang('Status')">
                                        @if($template->email_status == 1)
                                            <span
                                                class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span
                                                class="text--small badge font-weight-normal badge--warning">@lang('Disabled')</span>
                                        @endif
                                    </td>


                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.email-template.edit', $template->id) }}"
                                           class="icon-btn  ml-1 editGatewayBtn" data-toggle="tooltip" title=""
                                           data-original-title="@lang('Edit')">
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



@endsection


@push('script')
@endpush
