<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/admin/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">

        <div class="sidebar__logo">
            <a href="{{route('counterManager.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['ownerLogo']['path'] .'/'.auth()->guard('counterManager')->user()->owner->username.'.png')}}" alt="image"></a>
            <a href="{{route('counterManager.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="image"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive(['counterManager.dashboard', 'counterManager.sell.book'])}}">
                    <a href="{{route('counterManager.dashboard')}}" class="nav-link ">
                        <i class="menu-icon la la-ticket transform-rotate-minus-45"></i>
                        <span class="menu-title">@lang('Book Ticket')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('counterManager.trip*')}}">
                    <a href="{{route('counterManager.trip.index')}}" class="nav-link">
                        <i class="menu-icon la la-radiation-alt"></i>
                        <span class="menu-title">@lang('Trips')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('counterManager.statistics')}}">
                    <a href="{{route('counterManager.statistics')}}" class="nav-link">
                        <i class="menu-icon las la-chart-bar"></i>
                        <span class="menu-title">@lang('Statistics')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('counterManager.soldTickets*', 3)}}">
                        <i class="menu-icon las la-money-bill-wave"></i>
                        <span class="menu-title">@lang('Sold Tickets')</span>
                    </a>

                    <div class="sidebar-submenu {{menuActive('counterManager.soldTickets*', 2)}}">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('counterManager.soldTickets.todays')}}">
                                <a href="{{route('counterManager.soldTickets.todays')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Today's")</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('counterManager.soldTickets.all')}}">
                                <a href="{{route('counterManager.soldTickets.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Time')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('counterManager.soldTickets.cancelled')}}">
                                <a href="{{route('counterManager.soldTickets.cancelled')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Canceled")</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->
