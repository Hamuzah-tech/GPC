<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('auth-login', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('auth-verify', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('auth-password', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        View::composer('*', function ($view) {
            $settings = [];

            try {
                if (Schema::hasTable('settings')) {
                    $settings = Setting::map();
                }
            } catch (\Throwable $e) {
                $settings = [];
            }

            $view->with('settings', $settings);
        });

        \Illuminate\Pagination\Paginator::useBootstrapFour();
    }
}
