<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', static function (Request $request): array {
            $email = Str::lower((string) $request->input('email'));
            $emailAndIp = Str::transliterate($email.'|'.$request->ip());

            return [
                Limit::perMinute(20)->by('login-ip:'.$request->ip()),
                Limit::perMinute(5)->by('login-email:'.$emailAndIp),
            ];
        });
    }
}
