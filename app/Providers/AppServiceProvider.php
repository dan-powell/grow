<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Device;
use App\Models\Figure;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        Route::bind('device', fn ($value) => Device::where('id', $value)->firstOrFail());

        Route::bind('figure', fn ($value) => Figure::where('id', $value)->firstOrFail());

        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('View')
                    ->url(url('/'))
                    ->icon('heroicon-s-cog'),
                // ...
            ]);
        });
    }
}
