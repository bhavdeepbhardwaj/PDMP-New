<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        /*
        |--------------------------------------------------------------------------
        | Database
        |--------------------------------------------------------------------------
        */
        Schema::defaultStringLength(191);

        /*
        |--------------------------------------------------------------------------
        | Login Rate Limiting
        |--------------------------------------------------------------------------
        |
        | Maximum 5 login attempts per minute per IP + employee code.
        |
        */
        RateLimiter::for('login', function (Request $request) {

            return Limit::perMinute(5)
                ->by(
                    $request->ip().'|'.$request->input('employee_code')
                );
        });

        /*
        |--------------------------------------------------------------------------
        | Force HTTPS in Production
        |--------------------------------------------------------------------------
        |
        | Local development:
        |     HTTP is allowed.
        |
        | Production:
        |     Laravel generates HTTPS URLs.
        |
        */
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
