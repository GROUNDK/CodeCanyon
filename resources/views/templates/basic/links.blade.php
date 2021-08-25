@extends($activeTemplate .'layouts.master')

@section('content')

@php
    $breadcrumb = getContent('breadcrumb.content', true)->data_values;
@endphp

<section class="inner-banner-section banner-section bg-overlay-primary bg_img" data-background="{{ getImage('assets/images/frontend/breadcrumb/'.@$breadcrumb->image) }}">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12 text-center">
                <div class="banner-content">
                    <h2 class="title">
                        @lang($page_title)
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>



<div class="feature-section ptb-80">
    <div class="container">
        <p>@php echo (@$description) @endphp</p>
    </div>
</div>
@endsection


