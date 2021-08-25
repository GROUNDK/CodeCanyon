@php
$footerContent = getContent('footer.content', true)->data_values;
@endphp

<footer class="footer-section ptb-80 bg-overlay-primary-two bg_img"
    data-background="{{ getImage('assets/images/frontend/footer/'.$footerContent->image) }}">
    <div class="container">
        <div class="footer-area">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a class="site-logo site-title" href="{{ route('home') }}"><img
                                    src="{{ getImage('assets/images/logoIcon/logo.png') }}" alt="site-logo"></a>
                        </div>
                        <p>{{ __(@$footerContent->description_one) }}</p>
                        <div class="social-area">
                            <ul class="footer-social">
                                @php
                                $socials = getContent('social_icon.element');
                                @endphp
                                @foreach ($socials as $item)
                                <li>
                                    <a href="{{ $item->data_values->url }}">
                                        @php
                                        echo $item->data_values->icon
                                        @endphp
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="footer-widget useful-links">
                            <ul>
                                <li><a href="{{ route('owner.login') }}">@lang('Login As Owner')</a></li>
                                <li><a href="{{ route('co-owner.login') }}">@lang('Login As Co-Owner')</a></li>
                                <li><a href="{{ route('counterManager.login') }}">@lang('Login As Counter Manager')</a></li>
                                <li><a href="{{ route('driver.login') }}">@lang('Login As Driver')</a></li>
                                <li><a href="{{ route('supervisor.login') }}">@lang('Login As Supervisor')</a></li>
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="privacy-area">
    <div class="container">
        <div class="copyright-area d-flex flex-wrap align-items-center justify-content-between">
            <div class="copyright">
                <p>{{ preg_replace('!\d+!', date('Y') ,$footerContent->copyright_text) }}</p>
            </div>
            <ul class="copyright-list">
                @php
                $links = getContent('company_policies.element');
                @endphp
                @foreach ($links as $item)
                <li>
                    <a href="{{route('links', slug($item->data_values->page_title).'-'.$item->id)}}">@php echo
                        __($item->data_values->page_title) @endphp</a>
                </li>
                @endforeach


            </ul>
        </div>
    </div>
</div>
