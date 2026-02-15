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
            // استخدام تذكر للأبد إذا لم تتغير الإعدادات كثيراً، أو زيادة الوقت
            $settings = Cache::remember('site_settings', 86400, function () {
                // التأكد من استرجاع البيانات كمصفوفة مفتاح => قيمة
                return \App\Models\Setting::pluck('value', 'key')->toArray();
            });

            // مشاركة المتغير مع جميع الـ Views
            view()->share('settings', $settings);
        } catch (\Exception $e) {
            // معالجة الخطأ بصمت عند التثبيت لأول مرة أو مشاكل الاتصال بقاعدة البيانات
            // يمكن تسجيل الخطأ هنا للمساعدة في التصحيح
            // \Log::error('Settings loading error: ' . $e->getMessage());
        }
    }
}
