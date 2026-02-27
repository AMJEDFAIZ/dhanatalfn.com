<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use App\Services\SeoMetaService;
use App\Services\SeoPageService;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {

        $settings = Setting::pluck('value', 'key')->all();
        $services = Service::where('active', true)->orderBy('sort_order')->paginate(9);
        $meta_title = Setting::where('key', 'services_meta_title')->value('value') ?? 'الخدمات';
        $meta_description = Setting::where('key', 'services_meta_description')->value('value') ?? 'أفضل معلم دهانات وديكورات جدة حي الروضة ت: 0532791522.';
        $seoPage = app(SeoPageService::class)->getByKey('services_index');
        $meta_keywords = app(SeoMetaService::class)->keywordsString($seoPage?->keywordsForMeta() ?? []);
        $pageContentKeywords = $seoPage?->contentKeywords()->where('keywords.active', true)->get() ?? collect();
        // $meta_keywords = collect([
        //     $settings['site_name'] ?? null,
        //     'من نحن',
        //     'الفن الحديث ',
        //     'معلم دهانات جدة',
        //     'دهانات جدة',
        //     'ديكور جدة',
        //     'معلم بويه جدة',
        //     'دهانات داخلية جدة',
        //     'دهانات خارجية جدة',
        //     'سعر دهانات جدة',
        //     'أفضل معلم دهانات جدة',
        //     'ديكورات ودهانات جدة',
        //     'خدمات دهانات وديكور جدة',
        //     'ورق جدران جدة',
        //     'ترميمات جدة',
        //     'تشطيبات ودهانات جدة',

        //     // كلمات طويلة متعددة نوايا بحث (Long-tail)
        //     'سعر المتر دهان داخلي جدة',
        //     'أفضل دهانات داخلية للفلل جدة',
        //     'أعمال دهانات داخلية وجدران جدة',
        //     'خدمات ديكور داخلية وخارجية جدة',
        //     'دهانات مقاومة للرطوبة جدة',
        //     'ديكور بديل خشب جدة',
        //     'ديكور بديل رخام جدة',
        //     'بديل شيبورد جدة',
        //     'بديل حجر جدة',
        //     'تركيب ورق جدران ودهانات جدة',
        //     'دهانات فوم وبانوهات فوم جدة',

        //     // كلمات عامة غير محلية (خدمات + تصاميم)
        //     'معلم دهانات',
        //     'دهانات وديكورات',
        //     'دهانات داخلية',
        //     'دهانات خارجية',
        //     'ديكورات فوم',
        //     'ديكور بديل خشب',
        //     'ديكور بديل رخام',
        //     'ترميمات ودهانات',
        //     'تشطيبات عامة',
        //     'ورق جدران وديكور',
        //     'ألوان بويات داخلية',
        //     'أحدث ألوان دهانات',
        //     'خدمات دهانات للمنازل',
        //     'خدمات ديكور للمنازل',
        // ])->filter()->implode(', ');

        return view('services.index', compact('services', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'pageContentKeywords'));
    }

    public function getProjectsByService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $projects = $service->projects()->paginate(10);
        return response()->json($projects);
    }

    public function show($slug)
    {
        // جلب الإعدادات
        $settings = Setting::pluck('value', 'key')->all();
        $service = Service::with('faqs')->where('active', true)->where('slug', $slug)->first();

        if (! $service) {
            return redirect()->route('services.index')->with('error', 'عذراً، الخدمة المطلوبة غير موجودة.');
        }

        $projects = $service->projects()->where('active', true)->orderBy('sort_order')->paginate(9);

        // جلب 8 خدمات عشوائية
        // ملاحظة: إذا كنت في صفحة خدمة، يفضل استبعاد الخدمة الحالية باستخدام where('slug', '!=', $slug)
        $sidebarServices = Service::inRandomOrder()->limit(15)->get();
        $meta = app(SeoMetaService::class)->metaForModel($service, [
            'fallback_keywords' => array_filter([$settings['site_name'] ?? null, $service->title]),
        ]);
        $meta_title = $meta['meta_title'];
        $meta_description = $meta['meta_description'] ?: Str::limit(strip_tags($service->description ?? ''), 160);
        $meta_keywords = $meta['meta_keywords'];
        $contentKeywords = $service->contentKeywords()->where('keywords.active', true)->get();

        return view('services.show', compact('service', 'sidebarServices', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'projects', 'contentKeywords'));
    }
}
