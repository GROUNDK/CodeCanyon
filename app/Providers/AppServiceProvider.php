<?php

namespace App\Providers;

use App\GeneralSetting;
use App\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $activeTemplate = activeTemplate();

        $viewShare['general'] = GeneralSetting::first();
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => \App\Owner::banned()->count(),
                'email_unverified_users_count' => \App\Owner::emailUnverified()->count(),
                'sms_unverified_users_count'   => \App\Owner::smsUnverified()->count(),
                'pending_ticket_count'         => \App\SupportTicket::whereIN('status', [0,2])->count(),
                'pending_deposits_count'       => \App\Deposit::pending()->count(),
            ]);
        });
    }
}
