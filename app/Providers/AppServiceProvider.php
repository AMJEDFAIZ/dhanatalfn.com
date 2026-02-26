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
            $loadSettings = function (): array {
                try {
                    $settings = Cache::get('site_settings');
                    if (!is_array($settings) || $settings === []) {
                        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
                        if ($settings !== []) {
                            Cache::put('site_settings', $settings, 86400);
                        }
                    }
                    return is_array($settings) ? $settings : [];
                } catch (\Throwable) {
                    return [];
                }
            };

            view()->composer('*', function ($view) use ($loadSettings) {
                $view->with('settings', $loadSettings());
            });

            view()->composer('admin.layouts.admin', function ($view) {
                try {
                    $unreadMessagesCount = Cache::remember('admin_unread_messages_count', 60, function () {
                        return \App\Models\Message::where('is_read', false)->count();
                    });
                } catch (\Throwable) {
                    $unreadMessagesCount = 0;
                }

                $view->with('unreadMessagesCount', $unreadMessagesCount);
            });
        } catch (\Exception $e) {
            // معالجة الخطأ بصمت عند التثبيت لأول مرة أو مشاكل الاتصال بقاعدة البيانات
            // يمكن تسجيل الخطأ هنا للمساعدة في التصحيح
            // \Log::error('Settings loading error: ' . $e->getMessage());
        }
    }
}
