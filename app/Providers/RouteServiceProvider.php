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
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // for api front
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // for Admin
            Route::middleware('api')
                ->prefix('Admin')
                ->group(base_path('routes/admin.php'));

            // for Binance
            Route::middleware('api')
                ->prefix('binance')
                ->group(base_path('routes/binance.php'));


            Route::middleware('api')
                ->prefix('bot')
                ->group(base_path('routes/bot.php'));

            //  transaction
            Route::middleware('api')
                ->prefix('transaction')
                ->group(base_path('routes/transaction.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
