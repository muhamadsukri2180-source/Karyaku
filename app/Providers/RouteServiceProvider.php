<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Bebaskan limit login & register jika diakses dari localhost (Local Development)
        // Dan terapkan sistem ala Web Besar (Facebook/Google) untuk Production
        RateLimiter::for('auth', function (Request $request) {
            if (in_array($request->ip(), ['127.0.0.1', '::1'])) {
                return Limit::none(); // Tanpa batas di localhost
            }
            
            // Web besar melacak berdasarkan kombinasi Email + IP agar pengguna lain di WiFi yang sama tidak ikut terblokir
            $email = $request->input('email');
            $key = $email ? $email . '|' . $request->ip() : $request->ip();
            
            // Memberikan batas toleransi yang lebih wajar (10 kali percobaan per menit)
            return Limit::perMinute(10)->by($key); 
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
