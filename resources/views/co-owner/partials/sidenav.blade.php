<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/admin/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            @php
                $logo   = getOwnerLogo(auth()->guard('co-owner')->user()->owner);
            @endphp

            <a href="{{route('co-owner.dashboard')}}" class="sidebar__main-logo">
                <img src="{{ $logo }}" alt="@lang('image')">
            </a>
            <a href="{{route('co-owner.dashboard')}}" class="sidebar__logo-shape">
                <img src="{{ $logo }}" alt="@lang('image')">
            </a>

            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('co-owner.dashboard')}}">
                    <a href="{{route('co-owner.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>


                <li class="sidebar__menu-header"> <i class="menu-icon la la-users"></i>@lang('Manage Staff')</li>


                <li class="sidebar-menu-item {{menuActive('co-owner.supervisor*')}}">
                    <a href="{{route('co-owner.supervisor.index')}}" class="nav-link">
                        <i class="menu-icon las la-user-tie"></i>
                        <span class="menu-title">@lang('Supervisors')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('co-owner.driver*')}}">
                    <a href="{{route('co-owner.driver.index')}}" class="nav-link">
                        <i class="menu-icon las la-user-astronaut"></i>
                        <span class="menu-title">@lang('Drivers')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('co-owner.counter_manager*')}}">
                    <a href="{{route('co-owner.counter_manager.index')}}" class="nav-link">
                        <i class="menu-icon las la-user"></i>
                        <span class="menu-title">@lang('Counter Manager')</span>
                    </a>
                </li>


                <li class="sidebar__menu-header"> <i class="menu-icon la la-gopuram"></i>@lang('Manage Transport')</li>

                <li class="sidebar-menu-item {{menuActive(['co-owner.counter.index', 'co-owner.counter.trashed'])}}">
                    <a href="{{route('co-owner.counter.index')}}" class="nav-link">
                        <i class="menu-icon las la-landmark"></i>
                        <span class="menu-title">@lang('Counters')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('co-owner.fleet_manage*',3)}}">
                        <i class="menu-icon las la-bus"></i>
                        <span class="menu-title">@lang('Manage Fleets')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('co-owner.fleet_manage*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('co-owner.fleet_manage.seat_layout')}}">
                                <a href="{{route('co-owner.fleet_manage.seat_layout')}}" class="nav-link">
                                    <i class="menu-icon las la-couch"></i>
                                    <span class="menu-title">@lang('Seat Layouts')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['co-owner.fleet_manage.fleet_type', 'co-owner.fleet_manage.fleet_type.create', 'co-owner.fleet_manage.fleet_type.edit'])}}">
                                <a href="{{route('co-owner.fleet_manage.fleet_type')}}" class="nav-link">
                                    <i class="menu-icon lab la-buffer"></i>
                                    <span class="menu-title">@lang('Fleet Types')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['co-owner.fleet_manage.bus', 'co-owner.fleet_manage.bus.create'])}}">
                                <a href="{{route('co-owner.fleet_manage.bus')}}" class="nav-link">
                                    <i class="menu-icon las la-bus-alt"></i>
                                    <span class="menu-title">@lang('All Bus')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('co-owner.trip*',3)}}">
                        <i class="menu-icon la la-radiation-alt"></i>
                        <span class="menu-title">@lang('Manage Trips')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('co-owner.trip*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('co-owner.trip.route')}}">
                                <a href="{{ route('co-owner.trip.route') }}" class="nav-link">
                                    <i class="menu-icon las la-route"></i>
                                    <span class="menu-title">@lang('Routes')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('co-owner.trip.ticket*')}}">
                                <a href="{{route('co-owner.trip.ticket.price')}}" class="nav-link ">
                                    <i class="menu-icon las la-money-bill"></i>
                                    <span class="menu-title">@lang('Ticket Price')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('co-owner.trip.schedule')}}">
                                <a href="{{ route('co-owner.trip.schedule') }}" class="nav-link">
                                    <i class="menu-icon la la-calendar-week"></i>
                                    <span class="menu-title">@lang('Schedules')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['co-owner.trip.index','co-owner.trip.trashed', 'co-owner.trip.create'])}}">
                                <a href="{{ route('co-owner.trip.index') }}" class="nav-link">
                                    <i class="menu-icon las la-suitcase-rolling"></i>
                                    <span class="menu-title">@lang('Trip')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('co-owner.trip.bus.index')}}">
                                <a href="{{ route('co-owner.trip.bus.index') }}" class="nav-link">
                                    <i class="menu-icon las la-bus"></i>
                                    <span class="menu-title">@lang('Assign Buses')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{menuActive('co-owner.report*')}}">
                    <a href="{{route('co-owner.report.sale')}}" class="nav-link">
                        <i class="menu-icon las la-chart-area"></i>
                        <span class="menu-title">@lang('Report')</span>
                    </a>

                </li>
            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->
