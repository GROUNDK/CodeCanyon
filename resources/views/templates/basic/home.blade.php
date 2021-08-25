@extends($activeTemplate .'layouts.master')

@section('content')

@php
    $banner = getContent('banner.content', true)->data_values;
@endphp
    <section class="banner-section bg-overlay-primary bg_img" data-background="{{ getImage('assets/images/frontend/banner/'.$banner->image) }}">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="banner-content">

                        <h2 class="title">
                        @php echo trans(showContentHeading($banner->heading)) @endphp
                        </h2>
                        <h3 class="sub-title">{{ __($banner->subheading) }}</h3>
                        <div class="banner-btn">
                            <a href="{{ __($banner->button_one_link) }}" class="cmn-btn">{{ __($banner->button_one) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>

    @php
        $featureContent = getContent('features.content', true)->data_values;
        $features       = getContent('features.element');
    @endphp

    <section class="feature-section ptb-80" id="feature">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header">
                        <h2 class="section-title">@php echo trans(showContentHeading($featureContent->heading)) @endphp</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center ml-b-30">
                @foreach ($features as $item)
                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                    <div class="feature-item text-center">
                        <div class="feature-icon">
                            @php
                                echo $item->data_values->icon
                            @endphp
                        </div>
                        <div class="feature-content">
                            <h3 class="title">{{ __($item->data_values->title) }}</h3>
                            <p>{{ __($item->data_values->description) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    @php
        $stepContent    = getContent('steps.content', true)->data_values;
        $steps          = getContent('steps.element');
    @endphp
    <section class="process-section ptb-80 bg-overlay-primary-two bg_img" data-background="{{ getImage('assets/images/frontend/steps/'.$stepContent->image) }}" id="process">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header">
                        <h2 class="section-title">@php echo trans(showContentHeading($stepContent->heading)) @endphp</h2>
                    </div>
                </div>
            </div>

            <div class="process-area">
                <div class="row justify-content-center ml-b-30">
                @foreach ($steps as $item)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mrb-30">

                        <div class="process-item text-center">
                            <div class="process-icon">
                                @php echo $item->data_values->icon @endphp
                            </div>
                            <div class="process-content">
                                <h3 class="title">{{ __($item->data_values->title) }}</h3>
                                <span class="sub-title">@lang('Step') {{$loop->iteration}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </section>



@if($packages->count())
    <section class="pricing-section ptb-80" id="package">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header">
                        <h2 class="section-title">@php echo trans("Choose Your <span>Package</span>") @endphp</h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center ml-b-30">
                @foreach ($packages as $package)
                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                    <div class="pricing-item">
                        <div class="pricing-header text-center">
                            <h3 class="sub-title"><span>{{ __($package->name) }}</span></h3>
                            <h2 class="title">{{ $general->cur_sym }}{{ $package->price }}<span class="pricing-post">/ {{ $package->time_limit }} {{ __(getPackageLimitUnit($package->unit)) }}</span></h2>
                            <div class="pricing-shape">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="pricing-body text-center">
                            <ul class="pricing-list">
                                @foreach ($plan_features as $item)
                                    <li>@lang($item->name) </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="pricing-btn-area text-center">
                            <a href="@if(auth()->guard('owner')->check()) {{ route('owner.package.index') }} @else {{ route('owner.login') }} @endif" class="cmn-btn-active" >@lang('Subscribe Now!')
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
@endif

@php
    $inviteContent      = getContent('invite.content', true)->data_values;
@endphp
    <!-- call-to-action section start -->
    <section class="call-to-action-section call-to-action-section-two pd-t-60 pd-b-60">
        <div class="container">
            <div class="row justify-content-between align-items-center ml-b-30">
                <div class="col-lg-8 mrb-30">
                    <div class="call-to-action-content">
                        <h3 class="title">{{ __($inviteContent->text) }}</h3>
                    </div>
                </div>
                <div class="col-lg-4 mrb-30">
                    <div class="call-to-action-btn">
                        <a href="{{ $inviteContent->button_link }}" class="cmn-btn">{{ __($inviteContent->button_name) }}
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $serviceContent     = getContent('service.content', true)->data_values;
        $services           = getContent('service.element');
    @endphp

    <section class="choose-section ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header">
                        <h2 class="section-title"> @php echo trans(showContentHeading($serviceContent->heading)) @endphp </h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center ml-b-40">
                @foreach ($services as $item)
                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                    <div class="choose-item d-flex flex-wrap">
                        <div class="choose-icon">
                            @php echo $item->data_values->icon @endphp
                        </div>
                        <div class="choose-content">
                            <h3 class="title">{{ __($item->data_values->title) }}</h3>
                            <p>{{ __($item->data_values->description) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @php
        $testimonialContent     = getContent('testimonial.content', true)->data_values;
        $testimonial            = getContent('testimonial.element');
    @endphp
    <div class="client-section ptb-80 bg-overlay-primary-two bg_img" data-background="{{ getImage('assets/images/frontend/testimonial/'.$testimonialContent->image) }}">
        <div class="container">
            <div class="client-area">
                <div class="row justify-content-center align-items-end ml-b-20">
                    <div class="col-lg-12 text-center">
                        <div class="section-header">
                            <h2 class="section-title">{{ __($testimonialContent->heading) }}</h2>
                        </div>
                        <div class="client-slider">
                            <div class="swiper-wrapper">
                                @foreach ($testimonial as $testimonial)
                                <div class="swiper-slide">
                                    <div class="client-content text-center">
                                        <p>{{ __($testimonial->data_values->quote) }}</p>
                                        <h4 class="text-white mt-4">{{ __($testimonial->data_values->author) }}</h4>
                                        <h6 class="text-white font-italic font-weight-normal">{{ __($testimonial->data_values->designation) }}</h6>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $partners          = getContent('partner.element');
    @endphp
    <!-- brand-section start -->
    <div class="brand-section ptb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="brand-wrapper">
                        <div class="swiper-wrapper">
                            @foreach ($partners as $item)
                            <div class="swiper-slide">
                                <div class="BrandSlider">
                                    <div class="brand-item">
                                        <img src="{{ getImage('assets/images/frontend/partner/'.$item->data_values->image) }}" alt="logo images">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- brand-section end -->
@endsection
