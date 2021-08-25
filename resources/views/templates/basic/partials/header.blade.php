<!-- header-section start -->
<header class="header-section">
    <div class="header">

        <div class="header-bottom-area">
            <div class="container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{ route('home') }}"><img
                                src="{{ getImage('assets/images/logoIcon/logo.png') }}" alt="site-logo"></a>
                        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ml-auto mr-auto">
                                <li><a href="{{ route('home') }}" class="active">@lang('Home')</a></li>
                                <li><a href="#feature">@lang('Features')</a></li>
                                <li><a href="#process">@lang('Process')</a></li>
                                <li><a href="#package">@lang('Package')</a></li>
                            </ul>

                            <div class="header-action">
                                @if(!auth()->guard('owner')->check())
                                    <a href="{{ route('owner.login') }}" class="cmn-btn">@lang('Owner Login')
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </a>

                                    <a href="{{ route('owner.register') }}" class="cmn-btn">@lang('Register')
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </a>
                                @endif
                                @if(auth()->guard('owner')->check())
                                    <a href="{{ route('owner.dashboard') }}" class="cmn-btn">@lang('Owner Panel')</a>
                                @endif
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-section end -->
