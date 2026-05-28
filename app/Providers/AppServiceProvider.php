<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('register', function (Request $request) {

            return [

                // max 3 register per menit per IP
                Limit::perMinute(3)
                    ->by($request->ip()),

                // max 10 register per jam per username
                Limit::perHour(10)
                    ->by($request->username),
            ];
        });

        View::composer('*', function ($view) {

            if (Auth::check()) {

                $view->with(
                    'sidebar_menus',
                    session('sidebar_menus')
                );
            }
        });
    }
}
