<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

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
        // if (app()->environment('production')) {
        //     URL::forceScheme('https');
        // }

        try {
            // تخزين الإعدادات في الكاش لمدة يوم واحد (86400 ثانية)
            $settings = Cache::remember('site_settings', 86400, function () {
                return \App\Models\Setting::all()->pluck('value', 'key');
            });

            view()->share('settings', $settings);
        } catch (\Exception $e) {
            // معالجة الخطأ بصمت عند التثبيت لأول مرة
        }
    }
}
