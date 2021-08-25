<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('perfect_money', 'perfect_money\ProcessController@ipn')->name('perfect_money');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::post('skrill', 'skrill\ProcessController@ipn')->name('skrill');
    Route::post('paytm', 'paytm\ProcessController@ipn')->name('paytm');
    Route::post('payeer', 'payeer\ProcessController@ipn')->name('payeer');
    Route::post('paystack', 'paystack\ProcessController@ipn')->name('paystack');
    Route::post('voguepay', 'voguepay\ProcessController@ipn')->name('voguepay');
    Route::get('flutterwave/{trx}/{type}', 'flutterwave\ProcessController@ipn')->name('flutterwave');
    Route::post('razorpay', 'razorpay\ProcessController@ipn')->name('razorpay');
    Route::post('instamojo', 'instamojo\ProcessController@ipn')->name('instamojo');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
    Route::get('blockio', 'blockio\ProcessController@ipn')->name('blockio');
    Route::post('coinpayments', 'coinpayments\ProcessController@ipn')->name('coinpayments');
    Route::post('coinpayments_fiat', 'coinpayments_fiat\ProcessController@ipn')->name('coinpayments_fiat');
    Route::post('coingate', 'coingate\ProcessController@ipn')->name('coingate');
    Route::post('coinbase_commerce', 'coinbase_commerce\ProcessController@ipn')->name('coinbase_commerce');
    Route::get('mollie', 'mollie\ProcessController@ipn')->name('mollie');
});

/*
|--------------------------------------------------------------------------
| Start CoOwner Area
|--------------------------------------------------------------------------
*/

Route::namespace('CoOwner')->prefix('co-owner')->name('co-owner.')->group(function() {

    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login')->name('loginSubmit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');

    });

    Route::middleware('co-owner')->group(function(){
        Route::get('dashboard', 'CoOwnerController@dashboard')->name('dashboard');
        Route::get('profile', 'CoOwnerController@profile')->name('profile');
        Route::post('profile', 'CoOwnerController@profileUpdate')->name('profile.update');
        Route::get('password', 'CoOwnerController@password')->name('password');
        Route::post('password', 'CoOwnerController@passwordUpdate')->name('password.update');

        Route::get('dashboard', 'CoOwnerController@dashboard')->name('dashboard');

        Route::get('profile', 'CoOwnerController@profile')->name('profile');
        Route::post('profile', 'CoOwnerController@profileUpdate')->name('profile.update');
        Route::get('password', 'CoOwnerController@password')->name('password');
        Route::post('password', 'CoOwnerController@passwordUpdate')->name('password.update');

        Route::middleware(['ownerCheckPlan:co-owner'])->group(function(){

            //Supervisor
            Route::get('supervisors', 'SupervisorController@index')->name('supervisor.index');
            Route::get('supervisor/create', 'SupervisorController@create')->name('supervisor.create');
            Route::post('supervisor/create/{id}', 'SupervisorController@store')->name('supervisor.store');
            Route::get('supervisor/edit/{supervisor}', 'SupervisorController@edit')->name('supervisor.edit');
            Route::post('supervisor/delete/{id}', 'SupervisorController@destroy')->name('supervisor.remove');
            Route::get('supervisors/trashed', 'SupervisorController@trashed')->name('supervisor.trashed');

            //Driver
            Route::get('drivers', 'DriverController@index')->name('driver.index');
            Route::get('driver/create', 'DriverController@create')->name('driver.create');
            Route::post('driver/create/{id}', 'DriverController@store')->name('driver.store');
            Route::get('driver/edit/{driver}', 'DriverController@edit')->name('driver.edit');
            Route::post('driver/delete/{id}', 'DriverController@destroy')->name('driver.remove');
            Route::get('drivers/trashed', 'DriverController@trashed')->name('driver.trashed');

            //Counter
            Route::get('counters/', 'CounterController@index')->name('counter.index');
            Route::get('counter/create', 'CounterController@create')->name('counter.create');
            Route::post('counter/create/{id}', 'CounterController@store')->name('counter.store');
            Route::get('counter/edit/{counter_manager}', 'CounterController@edit')->name('counter.edit');

            //Counter Manager
            Route::get('counter/managers', 'CounterController@counterManager')->name('counter_manager.index');
            Route::get('counter/manager/create', 'CounterController@counterManagerCreate')->name('counter_manager.create');
            Route::post('counter/manager/create/{id}', 'CounterController@counterManagerStore')->name('counter_manager.store');
            Route::get('counter/manager/edit/{counter_manager}', 'CounterController@counterManagerEdit')->name('counter_manager.edit');
            Route::post('counter/manager/delete/{id}', 'CounterController@counterManagerDestroy')->name('counter_manager.remove');

            Route::get('counter/manager/assign', 'CounterController@assignCounterManager')->name('counter_manager.assign');

            //Fleets Manage
            Route::get('seat_layouts', 'FleetController@seatLayout')->name('fleet_manage.seat_layout');
            Route::post('seat_layouts/add/{id}', 'FleetController@seatLayoutAdd')->name('fleet_manage.seat_layout.add');
            Route::post('seat_layouts/delete/{id}', 'FleetController@seatLayoutRemove')->name('fleet_manage.seat_layout.remove');

            Route::get('fleet_types', 'FleetController@fleetType')->name('fleet_manage.fleet_type');
            Route::get('fleet_types/create', 'FleetController@fleetTypeCreate')->name('fleet_manage.fleet_type.create');
            Route::get('fleet_types/edit/{id}', 'FleetController@fleetTypeEdit')->name('fleet_manage.fleet_type.edit');
            Route::post('fleet_types/create/{id}', 'FleetController@fleetTypeStore')->name('fleet_manage.fleet_type.store');

            Route::get('fleet_types/bus', 'FleetController@bus')->name('fleet_manage.bus');
            Route::get('fleet_types/bus/create', 'FleetController@busCreate')->name('fleet_manage.bus.create');
            Route::post('fleet_types/bus/create/{id}', 'FleetController@busStore')->name('fleet_manage.bus.store');
            Route::get('fleet_types/bus/edit/{id}', 'FleetController@busEdit')->name('fleet_manage.bus.edit');

            //Trip Manage
            Route::get('trips', 'TripController@index')->name('trip.index');
            Route::get('trips/serarch', 'TripController@search')->name('trip.search');
            Route::get('trips/trashed', 'TripController@trashed')->name('trip.trashed');

            Route::get('trip/create/', 'TripController@create')->name('trip.create');
            Route::post('trip/create/{id}', 'TripController@store')->name('trip.store');
            Route::get('trip/edit/{id}', 'TripController@edit')->name('trip.edit');
            Route::post('trip/remove/{id}', 'TripController@remove')->name('trip.remove');

            //Trip Manage - Stoppage
            Route::get('trip/stoppages', 'TripController@stoppage')->name('trip.stoppage');
            Route::get('trip/stoppages/trashed', 'TripController@stoppageTrashed')->name('trip.stoppage.trashed');
            Route::post('trip/stoppage/create/{id}', 'TripController@stoppageStore')->name('trip.stoppage.store');
            Route::post('trip/stoppage/remove/{id}', 'TripController@stoppageRemove')->name('trip.stoppage.remove');

            //Trip Manage - Routes
            Route::get('trip/routes', 'TripController@route')->name('trip.route');
            Route::get('trip/route/create/', 'TripController@routeCreate')->name('trip.route.create');
            Route::post('trip/route/create/{id}', 'TripController@routeStore')->name('trip.route.store');
            Route::get('trip/route/edit/{id}', 'TripController@routeEdit')->name('trip.route.edit');

            //Trip Manage - Schedules
            Route::get('trip/schedules', 'ScheduleController@index')->name('trip.schedule');
            Route::post('trip/schedule/create/{id}', 'ScheduleController@store')->name('trip.schedule.store');
            Route::post('trip/schedule/remove/{id}', 'ScheduleController@destroy')->name('trip.schedule.remove');

            //Trip Management - Bus Assign
            Route::get('trip/vehicles', 'TripController@bus')->name('trip.bus.index');
            Route::get('trip/vehicles/trashed', 'TripController@busTrashed')->name('trip.bus.trashed');
            Route::get('trip/vehicle/create/', 'TripController@busCreate')->name('trip.bus.create');
            Route::post('trip/vehicle/create/{id}', 'TripController@busStore')->name('trip.bus.store');
            Route::get('trip/vehicle/edit/{id}', 'TripController@busEdit')->name('trip.bus.edit');
            Route::post('trip/vehicle/remove/{id}', 'TripController@busRemove')->name('trip.bus.remove');


            //Ticket Management
            Route::get('ticket/prices', 'BusTicketController@prices')->name('trip.ticket.price');
            Route::get('ticket/price/', 'BusTicketController@create')->name('trip.ticket.price.create');
            Route::post('ticket/price/', 'BusTicketController@store')->name('trip.ticket.price.store');
            Route::get('ticket/price/edit/{id}', 'BusTicketController@edit')->name('trip.ticket.price.edit');

            Route::post('ticket/prices/edit/{id}', 'BusTicketController@updatePrices')->name('trip.ticket.prices.update');

            Route::post('ticket/price/delete/{id}', 'BusTicketController@destroy')->name('trip.ticket.price.remove');
            Route::get('route_data', 'BusTicketController@getRouteData')->name('trip.ticket.get_route_data');
            Route::get('check_price', 'BusTicketController@checkTicketPrice')->name('trip.ticket.check_price');

            //Report
            Route::get('report/sale', 'SalesReportController@sale')->name('report.sale');
            Route::get('report/sale/{id}', 'SalesReportController@saleDetail')->name('report.sale.detail');

            Route::post('report/filter', 'SalesReportController@filterSales')->name('report.sale.filter');
            Route::get('report/filtered', 'SalesReportController@filteredData')->name('report.sale.filtered');

            Route::get('report/periodic/', 'SalesReportController@periodic')->name('report.periodic');

        });
    });
});

/*
|--------------------------------------------------------------------------
| Start Driver Area
|--------------------------------------------------------------------------
*/


Route::namespace('Driver')->prefix('driver')->name('driver.')->group(function() {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login')->name('loginSubmit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('driver')->group(function(){
        Route::get('dashboard', 'DriverController@dashboard')->name('dashboard');
        Route::get('profile', 'DriverController@profile')->name('profile');
        Route::post('profile', 'DriverController@profileUpdate')->name('profile.update');
        Route::get('password', 'DriverController@password')->name('password');
        Route::post('password', 'DriverController@passwordUpdate')->name('password.update');
        Route::get('trips', 'DriverController@trips')->name('trips');
        Route::get('trip/view/{id}/{slug}', 'DriverController@viewTrips')->name('trips.view');

    });
});

/*
|--------------------------------------------------------------------------
| Start CounterManager Area
|--------------------------------------------------------------------------
*/

Route::namespace('Supervisor')->prefix('supervisor')->name('supervisor.')->group(function() {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login')->name('loginSubmit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('supervisor')->group(function(){
        Route::get('dashboard', 'SupervisorController@dashboard')->name('dashboard');
        Route::get('profile', 'SupervisorController@profile')->name('profile');
        Route::post('profile', 'SupervisorController@profileUpdate')->name('profile.update');
        Route::get('password', 'SupervisorController@password')->name('password');
        Route::post('password', 'SupervisorController@passwordUpdate')->name('password.update');

        Route::get('trips', 'SupervisorController@trips')->name('trips');
        Route::get('trip/view/{id}/{slug}', 'SupervisorController@viewTrips')->name('trips.view');

    });
});



Route::namespace('CounterManager')->prefix('counter')->name('counterManager.')->group(function() {
    Route::namespace('Auth')->group(function () {
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login')->name('loginSubmit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('counterManager')->group(function(){
        Route::get('profile', 'CounterManagerController@profile')->name('profile');
        Route::post('profile', 'CounterManagerController@profileUpdate')->name('profile.update');
        Route::get('password', 'CounterManagerController@password')->name('password');
        Route::post('password', 'CounterManagerController@passwordUpdate')->name('password.update');

        Route::get('/', 'CounterManagerController@sell')->name('dashboard');
        Route::post('/', 'CounterManagerController@searchTrip')->name('sell.search');
        Route::get('statistics', 'CounterManagerController@statistics')->name('statistics');

        //Trips
        Route::middleware(['ownerCheckPlan:counterManager'])->group(function(){
            Route::get('trips', 'CounterManagerController@trips')->name('trip.index');
            Route::get('sell/ticket/book/{ticket_prices_id}/{id}/{slug}', 'CounterManagerController@book')->name('sell.book');

            Route::get('sellbydate/ticket/book/{ticket_prices_id}/{id}', 'CounterManagerController@bookByDate')->name('sell.book.bydate');


            Route::post('sell/ticket/book/{id}', 'CounterManagerController@booked')->name('sell.book.booked');
            Route::get('sell/ticket/print/{id}', 'CounterManagerController@ticketPrint')->name('sell.ticket.print');

            Route::get('trip/ticket/get-ticket-price', 'CounterManagerController@getTicketPrice')->name('ticket.get-price');

            Route::get('sold-tickets/todays', 'CounterManagerController@todaysSold')->name('soldTickets.todays');
            Route::get('sold-tickets/alltime', 'CounterManagerController@allSold')->name('soldTickets.all');

            Route::get('sold-tickets/cancelled', 'CounterManagerController@cancelledSold')->name('soldTickets.cancelled');

            Route::post('sold-tickets/cancel', 'CounterManagerController@cancelSold')->name('soldTickets.cancel');
            Route::post('sold-tickets/rebook', 'CounterManagerController@rebookSold')->name('soldTickets.rebook');
            Route::post('sold-tickets/filter', 'CounterManagerController@filter')->name('soldTickets.filter');
            Route::get('sold-tickets/filtered', 'CounterManagerController@filtered')->name('soldTickets.filtered');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Start Owner Area
|--------------------------------------------------------------------------
*/

Route::middleware('owner')->prefix('owner')->name('owner.')->group(function(){
    Route::any('payment', 'Gateway\PaymentController@deposit')->name('deposit');
    Route::post('payment/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
    Route::get('payment/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
    Route::get('payment/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
    Route::get('payment/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
    Route::post('payment/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
    Route::get('payment/history', 'Owner\OwnerController@depositHistory')->name('deposit.history');
});



Route::namespace('Owner')->prefix('owner')->name('owner.')->group(function() {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::get('/login', 'LoginController@showLoginForm')->name('loginSubmit');
        Route::post('/login', 'LoginController@login')->name('loginSubmit');

        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->middleware('regStatus');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');

        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify_sms');
    });


    Route::middleware(['checkStatus', 'owner'])->group(function(){


        Route::get('dashboard', 'OwnerController@dashboard')->name('dashboard');

        Route::get('package', 'OwnerController@package')->name('package.index');
        Route::get('packages/purchased', 'OwnerController@packageActive')->name('package.active');
        Route::post('package', 'OwnerController@packageBuy')->name('package');

        Route::get('profile', 'OwnerController@profile')->name('profile');
        Route::post('profile', 'OwnerController@profileUpdate')->name('profile.update');
        Route::get('password', 'OwnerController@password')->name('password');
        Route::post('password', 'OwnerController@passwordUpdate')->name('password.update');


        // Owner Support Ticket
        Route::prefix('support_ticket')->group(function () {
            Route::get('/', 'OwnerTicketController@supportTicket')->name('ticket');
            Route::get('/new', 'OwnerTicketController@openSupportTicket')->name('ticket.open');
            Route::post('/create', 'OwnerTicketController@storeSupportTicket')->name('ticket.store');
            Route::get('/view/{ticket}', 'OwnerTicketController@viewTicket')->name('ticket.view');
            Route::post('/reply/{ticket}', 'OwnerTicketController@replyTicket')->name('ticket.reply');
            Route::get('/download/{ticket}', 'OwnerTicketController@ticketDownload')->name('ticket.download');
        });

        Route::middleware(['ownerCheckPlan'])->group(function(){

            //Settings
            Route::get('settings', 'OwnerController@generalSettings')->name('settings.general');
            Route::post('settings', 'OwnerController@generalSettingsUpdate')->name('settings.general');

            //CoOwner
            Route::get('co-owners', 'CoOwnerController@index')->name('co-owner.index');
            Route::get('co-owner/create', 'CoOwnerController@create')->name('co-owner.create');
            Route::post('co-owner/create/{id}', 'CoOwnerController@store')->name('co-owner.store');
            Route::get('co-owner/edit/{co_admin}', 'CoOwnerController@edit')->name('co-owner.edit');
            Route::post('co-owner/delete/{id}', 'CoOwnerController@destroy')->name('co-owner.remove');
            Route::get('co-owners/trashed', 'CoOwnerController@trashed')->name('co-owner.trashed');

            //Supervisor
            Route::get('supervisors', 'SupervisorController@index')->name('supervisor.index');
            Route::get('supervisor/create', 'SupervisorController@create')->name('supervisor.create');
            Route::post('supervisor/create/{id}', 'SupervisorController@store')->name('supervisor.store');
            Route::get('supervisor/edit/{supervisor}', 'SupervisorController@edit')->name('supervisor.edit');
            Route::post('supervisor/delete/{id}', 'SupervisorController@destroy')->name('supervisor.remove');
            Route::get('supervisors/trashed', 'SupervisorController@trashed')->name('supervisor.trashed');

            //Driver
            Route::get('drivers', 'DriverController@index')->name('driver.index');
            Route::get('driver/create', 'DriverController@create')->name('driver.create');
            Route::post('driver/create/{id}', 'DriverController@store')->name('driver.store');
            Route::get('driver/edit/{driver}', 'DriverController@edit')->name('driver.edit');
            Route::post('driver/delete/{id}', 'DriverController@destroy')->name('driver.remove');
            Route::get('drivers/trashed', 'DriverController@trashed')->name('driver.trashed');

            //Counter
            Route::get('counters/', 'CounterController@index')->name('counter.index');
            Route::get('counter/create', 'CounterController@create')->name('counter.create');
            Route::post('counter/create/{id}', 'CounterController@store')->name('counter.store');
            Route::get('counter/edit/{counter_manager}', 'CounterController@edit')->name('counter.edit');

            //Counter Manager
            Route::get('counter/managers', 'CounterController@counterManager')->name('counter_manager.index');
            Route::get('counter/manager/create', 'CounterController@counterManagerCreate')->name('counter_manager.create');
            Route::post('counter/manager/create/{id}', 'CounterController@counterManagerStore')->name('counter_manager.store');
            Route::get('counter/manager/edit/{counter_manager}', 'CounterController@counterManagerEdit')->name('counter_manager.edit');
            Route::post('counter/manager/delete/{id}', 'CounterController@counterManagerDestroy')->name('counter_manager.remove');
            Route::get('counter/manager/assign', 'CounterController@assignCounterManager')->name('counter_manager.assign');

            //Fleets Manage
            Route::get('seat_layouts', 'FleetController@seatLayout')->name('fleet_manage.seat_layout');
            Route::post('seat_layouts/add/{id}', 'FleetController@seatLayoutAdd')->name('fleet_manage.seat_layout.add');
            Route::post('seat_layouts/delete/{id}', 'FleetController@seatLayoutRemove')->name('fleet_manage.seat_layout.remove');

            Route::get('fleet_types', 'FleetController@fleetType')->name('fleet_manage.fleet_type');
            Route::get('fleet_types/create', 'FleetController@fleetTypeCreate')->name('fleet_manage.fleet_type.create');
            Route::get('fleet_types/edit/{id}', 'FleetController@fleetTypeEdit')->name('fleet_manage.fleet_type.edit');
            Route::post('fleet_types/create/{id}', 'FleetController@fleetTypeStore')->name('fleet_manage.fleet_type.store');

            Route::get('fleet_types/vehicle', 'FleetController@bus')->name('fleet_manage.bus');
            Route::get('fleet_types/vehicle/create', 'FleetController@busCreate')->name('fleet_manage.bus.create');
            Route::post('fleet_types/vehicle/create/{id}', 'FleetController@busStore')->name('fleet_manage.bus.store');
            Route::get('fleet_types/vehicle/edit/{id}', 'FleetController@busEdit')->name('fleet_manage.bus.edit');

            //Trip Manage
            Route::get('trips', 'TripController@index')->name('trip.index');
            Route::get('trips/serarch', 'TripController@search')->name('trip.search');
            Route::get('trips/trashed', 'TripController@trashed')->name('trip.trashed');

            Route::get('trip/create/', 'TripController@create')->name('trip.create');
            Route::post('trip/create/{id}', 'TripController@store')->name('trip.store');
            Route::get('trip/edit/{id}', 'TripController@edit')->name('trip.edit');
            Route::post('trip/remove/{id}', 'TripController@remove')->name('trip.remove');

            //Trip Manage - Stoppage
            Route::get('trip/stoppages', 'TripController@stoppage')->name('trip.stoppage');
            Route::get('trip/stoppages/trashed', 'TripController@stoppageTrashed')->name('trip.stoppage.trashed');
            Route::post('trip/stoppage/create/{id}', 'TripController@stoppageStore')->name('trip.stoppage.store');
            Route::post('trip/stoppage/remove/{id}', 'TripController@stoppageRemove')->name('trip.stoppage.remove');

            //Trip Manage - Routes
            Route::get('trip/routes', 'TripController@route')->name('trip.route');
            Route::get('trip/route/create/', 'TripController@routeCreate')->name('trip.route.create');
            Route::post('trip/route/create/{id}', 'TripController@routeStore')->name('trip.route.store');
            Route::get('trip/route/edit/{id}', 'TripController@routeEdit')->name('trip.route.edit');

            //Trip Manage - Schedules
            Route::get('trip/schedules', 'ScheduleController@index')->name('trip.schedule');
            Route::post('trip/schedule/create/{id}', 'ScheduleController@store')->name('trip.schedule.store');
            Route::post('trip/schedule/remove/{id}', 'ScheduleController@destroy')->name('trip.schedule.remove');

            //Trip Management - Bus Assign
            Route::get('trip/vehicles', 'TripController@bus')->name('trip.bus.index');
            Route::get('trip/vehicles/trashed', 'TripController@busTrashed')->name('trip.bus.trashed');
            Route::get('trip/vehicle/create/', 'TripController@busCreate')->name('trip.bus.create');
            Route::post('trip/vehicle/create/{id}', 'TripController@busStore')->name('trip.bus.store');
            Route::get('trip/vehicle/edit/{id}', 'TripController@busEdit')->name('trip.bus.edit');
            Route::post('trip/vehicle/remove/{id}', 'TripController@busRemove')->name('trip.bus.remove');


            //Ticket Management
            Route::get('ticket/prices', 'BusTicketController@prices')->name('trip.ticket.price');
            Route::get('ticket/price/', 'BusTicketController@create')->name('trip.ticket.price.create');
            Route::post('ticket/price/', 'BusTicketController@store')->name('trip.ticket.price.store');
            Route::get('ticket/price/edit/{id}', 'BusTicketController@edit')->name('trip.ticket.price.edit');
            Route::post('ticket/prices/edit/{id}', 'BusTicketController@updatePrices')->name('trip.ticket.prices.update');
            Route::post('ticket/price/delete/{id}', 'BusTicketController@destroy')->name('trip.ticket.price.remove');
            Route::get('route_data', 'BusTicketController@getRouteData')->name('trip.ticket.get_route_data');
            Route::get('check_price', 'BusTicketController@checkTicketPrice')->name('trip.ticket.check_price');

            //Report
            Route::get('report/sale', 'SalesReportController@sale')->name('report.sale');
            Route::get('report/sale/{id}', 'SalesReportController@saleDetail')->name('report.sale.detail');

            Route::post('report/filter', 'SalesReportController@filterSales')->name('report.sale.filter');
            Route::get('report/filtered', 'SalesReportController@filteredData')->name('report.sale.filtered');

            Route::get('report/periodic/', 'SalesReportController@periodic')->name('report.periodic');

        });
    });

});

/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');

        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Package
        Route::get('packages', 'PackageController@index')->name('package.index');
        Route::get('package/create', 'PackageController@create')->name('package.create');
        Route::post('package/create/{id}', 'PackageController@store')->name('package.store');

        Route::get('package/edit/{id}', 'PackageController@index')->name('package.edit');
        Route::get('packages/sold', 'PackageController@soldPackage')->name('package.sold');


        Route::get('features', 'FeatureController@index')->name('feature.index');
        Route::get('feature/create', 'FeatureController@create')->name('feature.create');
        Route::post('feature/create/{id}', 'FeatureController@store')->name('feature.store');

        Route::get('feature/edit/{id}', 'FeatureController@index')->name('feature.edit');
        Route::post('feature/remove/{feature}', 'FeatureController@remove')->name('feature.remove');

        // Users Manager
        Route::get('owners', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('owners/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('owners/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('owners/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.emailVerified');
        Route::get('owners/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.emailUnverified');
        Route::get('owners/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.smsUnverified');
        Route::get('owners/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.smsVerified');

        Route::get('owners/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('owner/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('owner/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::get('owner/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('owner/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('owner/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('owner/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');


        // Login History
        Route::get('owners/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');
        Route::get('owners/login/history', 'ManageUsersController@loginHistory')->name('users.login.history');
        Route::get('owners/login/ipHistory/{ip}', 'ManageUsersController@loginIpHistory')->name('users.login.ipHistory');

        Route::get('owners/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('owners/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');

        // DEPOSIT SYSTEM
        Route::get('deposit', 'DepositController@deposit')->name('deposit.list');
        Route::get('deposit/pending', 'DepositController@pending')->name('deposit.pending');
        Route::get('deposit/rejected', 'DepositController@rejected')->name('deposit.rejected');
        Route::get('deposit/approved', 'DepositController@approved')->name('deposit.approved');
        Route::get('deposit/successful', 'DepositController@successful')->name('deposit.successful');
        Route::get('deposit/details/{id}', 'DepositController@details')->name('deposit.details');

        Route::post('deposit/reject', 'DepositController@reject')->name('deposit.reject');
        Route::post('deposit/approve', 'DepositController@approve')->name('deposit.approve');
        Route::get('deposit/{scope}/search', 'DepositController@search')->name('deposit.search');

        // Deposit Gateway
        Route::get('deposit/gateway', 'GatewayController@index')->name('deposit.gateway.index');
        Route::get('deposit/gateway/edit/{alias}', 'GatewayController@edit')->name('deposit.gateway.edit');
        Route::post('deposit/gateway/update/{code}', 'GatewayController@update')->name('deposit.gateway.update');
        Route::post('deposit/gateway/remove/{code}', 'GatewayController@remove')->name('deposit.gateway.remove');
        Route::post('deposit/gateway/activate', 'GatewayController@activate')->name('deposit.gateway.activate');
        Route::post('deposit/gateway/deactivate', 'GatewayController@deactivate')->name('deposit.gateway.deactivate');

        // Manual Methods
        Route::get('deposit/gateway/manual', 'ManualGatewayController@index')->name('deposit.manual.index');
        Route::get('deposit/gateway/manual/new', 'ManualGatewayController@create')->name('deposit.manual.create');
        Route::post('deposit/gateway/manual/new', 'ManualGatewayController@store')->name('deposit.manual.store');
        Route::get('deposit/gateway/manual/edit/{alias}', 'ManualGatewayController@edit')->name('deposit.manual.edit');
        Route::post('deposit/gateway/manual/update/{id}', 'ManualGatewayController@update')->name('deposit.manual.update');
        Route::post('deposit/gateway/manual/activate', 'ManualGatewayController@activate')->name('deposit.manual.activate');
        Route::post('deposit/gateway/manual/deactivate', 'ManualGatewayController@deactivate')->name('deposit.manual.deactivate');

        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');

        Route::get('report/sales', 'ReportController@sales')->name('report.sales');
        Route::get('report/sales/search', 'ReportController@salesSearch')->name('report.sales.search');

        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');

        // General Setting
        Route::get('setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('setting', 'GeneralSettingController@update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo-icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo-icon');


        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email-template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email-template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email-template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email-template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email-template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email-template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email-template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email-template.sendTestMail');

        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsSetting')->name('sms-template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsSettingUpdate')->name('sms-template.global');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms-template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms-template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms-template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('email-template.sendTestSMS');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');

        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');
        });

    });
});

Route::get('/', 'SiteController@index')->name('home');
Route::get('links/{slug}', 'SiteController@links')->name('links');
