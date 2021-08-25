<div class="sidebar capsule--rounded bg_img overlay--dark" data-background="{{asset('assets/admin/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>


    <div class="sidebar__inner">
        <div class="sidebar__logo">
            @php
                $owner  = auth()->guard('owner')->user();
                $logo   = getOwnerLogo($owner);
            @endphp
            <a href="{{route('owner.dashboard')}}" class="sidebar__main-logo">
                <img src="{{ $logo }}" alt="@lang('image')">
            </a>
            <a href="{{route('owner.dashboard')}}" class="sidebar__logo-shape">
                <img src="{{ $logo }}" alt="@lang('image')">
            </a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('owner.dashboard')}}">
                    <a href="{{route('owner.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="nav-link {{menuActive('owner.package*',3)}}">
                        <i class="menu-icon las la-cube"></i>
                        <span class="menu-title">@lang('Packages')</span>
                    </a>

                    <div class="sidebar-submenu {{ menuActive('owner.package.*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['owner.package.active'])}}">
                                <a href="{{route('owner.package.active')}}" class="nav-link">
                                    <i class="menu-icon las la-check-circle"></i>
                                    <span class="menu-title">@lang('Purchased Package')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('owner.package.index')}}">
                                <a href="{{route('owner.package.index')}}" class="nav-link">
                                    <i class="menu-icon las la-cart-plus"></i>
                                    <span class="menu-title">@lang('Buy Package')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.ticket*',3)}}">
                        <i class="menu-icon la la-ticket transform-rotate-minus-45"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('owner.ticket*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('owner.ticket')}} ">
                                <a href="{{route('owner.ticket')}}" class="nav-link">
                                    <i class="menu-icon las la-tasks"></i>
                                    <span class="menu-title">@lang('My Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('owner.ticket.open')}} ">
                                <a href="{{route('owner.ticket.open')}}" class="nav-link">
                                    <i class="menu-icon las la-comment"></i>
                                    <span class="menu-title">@lang('New Ticket')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="sidebar__menu-header"> @lang('Settings')</li>

                <li class="sidebar-menu-item {{menuActive('owner.settings.general')}}">
                    <a href="{{route('owner.settings.general')}}" class="nav-link">
                        <i class="menu-icon la la-tools"></i>
                        <span class="menu-title">@lang('General')</span>
                    </a>
                </li>

                <li class="sidebar__menu-header">@lang('Manage Staff')</li>

                <li class="sidebar-menu-item {{menuActive('owner.co-owner*')}}">
                    <a href="{{route('owner.co-owner.index')}}" class="nav-link ">
                        <i class="menu-icon las la-user-friends"></i>
                        <span class="menu-title">@lang('Co-Owners')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('owner.supervisor*')}}">
                    <a href="{{route('owner.supervisor.index')}}" class="nav-link">
                        <i class="menu-icon las la-user-tie"></i>
                        <span class="menu-title">@lang('Supervisors')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('owner.driver*')}}">
                    <a href="{{route('owner.driver.index')}}" class="nav-link">
                        <i class="menu-icon las la-user-astronaut"></i>
                        <span class="menu-title">@lang('Drivers')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('owner.counter_manager*')}}">
                    <a href="{{route('owner.counter_manager.index')}}" class="nav-link">
                        <i class="menu-icon las la-user"></i>
                        <span class="menu-title">@lang('Counter Managers')</span>
                    </a>
                </li>

                <li class="sidebar__menu-header">@lang('Manage Transport')</li>

                <li class="sidebar-menu-item {{menuActive(['owner.counter.index', 'owner.counter.trashed'])}}">
                    <a href="{{route('owner.counter.index')}}" class="nav-link">
                        <i class="menu-icon las la-landmark"></i>
                        <span class="menu-title">@lang('Counters')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('owner.fleet_manage*',3)}}">
                        <i class="menu-icon las la-bus"></i>
                        <span class="menu-title">@lang('Manage Fleets')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('owner.fleet_manage*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('owner.fleet_manage.seat_layout')}}">
                                <a href="{{route('owner.fleet_manage.seat_layout')}}" class="nav-link">
                                    <i class="menu-icon las la-couch"></i>
                                    <span class="menu-title">@lang('Seat Layouts')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['owner.fleet_manage.fleet_type', 'owner.fleet_manage.fleet_type.create', 'owner.fleet_manage.fleet_type.edit'])}}">
                                <a href="{{route('owner.fleet_manage.fleet_type')}}" class="nav-link">
                                    <i class="menu-icon lab la-buffer"></i>
                                    <span class="menu-title">@lang('Fleet Types')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['owner.fleet_manage.bus', 'owner.fleet_manage.bus.create'])}}">
                                <a href="{{route('owner.fleet_manage.bus')}}" class="nav-link">
                                    <i class="menu-icon las la-bus-alt"></i>
                                    <span class="menu-title">@lang('All Vehicles')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('owner.trip*',3)}}">
                        <i class="menu-icon la la-radiation-alt"></i>
                        <span class="menu-title">@lang('Manage Trips')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('owner.trip*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('owner.trip.route*')}}">
                                <a href="{{ route('owner.trip.route') }}" class="nav-link">
                                    <i class="menu-icon las la-route"></i>
                                    <span class="menu-title">@lang('Routes')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('owner.trip.ticket*')}}">
                                <a href="{{route('owner.trip.ticket.price')}}" class="nav-link ">
                                    <i class="menu-icon las la-money-bill"></i>
                                    <span class="menu-title">@lang('Ticket Price')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('owner.trip.schedule*')}}">
                                <a href="{{ route('owner.trip.schedule') }}" class="nav-link">
                                    <i class="menu-icon la la-calendar-week"></i>
                                    <span class="menu-title">@lang('Schedules')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive(['owner.trip.index','owner.trip.trashed', 'owner.trip.create'])}}">
                                <a href="{{ route('owner.trip.index') }}" class="nav-link">
                                    <i class="menu-icon las la-suitcase-rolling"></i>
                                    <span class="menu-title">@lang('Trip')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('owner.trip.bus.index')}}">
                                <a href="{{ route('owner.trip.bus.index') }}" class="nav-link">
                                    <i class="menu-icon las la-bus"></i>
                                    <span class="menu-title">@lang('Assign Vehicles')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar__menu-header"></i>@lang('Reports')</li>

                <li class="sidebar-menu-item {{menuActive('owner.report*')}}">
                    <a href="{{route('owner.report.sale')}}" class="nav-link">
                        <i class="menu-icon las la-chart-bar"></i>
                        <span class="menu-title">@lang('Sales Report')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{menuActive('owner.deposit.history')}}">
                    <a href="{{route('owner.deposit.history')}}" class="nav-link">
                        <i class="menu-icon las la-money-bill"></i>
                        <span class="menu-title">@lang('Payment History')</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->
