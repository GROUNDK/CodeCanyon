@extends('admin.layouts.app')

@section('panel')

    <div class="row mb-none-30">


        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive table-responsive--sm">
                        <table class="default-data-table table ">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($sms_templates as $template)
                                <tr>
                                    <td data-label="@lang('Name')">
                                        {{ $template->name }}
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($template->sms_status == 1)
                                            <span class="font-weight-normal text--small badge--success">@lang('Active')</span>
                                        @else
                                            <span class="font-weight-normal text--small badge--warning">@lang('Disabled')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.sms-template.edit', $template->id) }}"
                                           class="icon-btn ml-1 editGatewayBtn" data-toggle="tooltip" title=""
                                           data-original-title="@lang('Edit')">
                                            <i class="la la-pencil"></i>
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ trans($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


