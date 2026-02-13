<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {

        $settings = Setting::pluck('value', 'key')->all();
        $services = Service::where('active', true)->orderBy('sort_order')->paginate(9);
        $meta_title = Setting::where('key', 'services_meta_title')->value('value') ?? 'الخدمات';
        $meta_description = Setting::where('key', 'services_meta_description')->value('value') ?? 'أفضل معلم دهانات وديكورات جدة حي الروضة ت: 0532791522.';
        // $meta_keywords = collect($services->pluck('title')->take(5))->implode(', ');



        $meta_keywords = collect($services->pluck('title')->take(7))->merge([
            $settings['site_name'] ?? null,
            'مقاول دهانات',
            'مقاول بجدة',
            'دهان واجهات',
            'مقاول دهانات بجدة',
            'مقاول جبس بورد',
            'مقاول اصباغ',
            'مقاول بناء جدة',
            'مقاول ملاحق جده',
            'مقاول بناء في جدة',
            'افضل مقاول في جدة',
            'معلم بناء جدة',
            'مطلوب مقاول جبس بورد',
            'مطلوب مقاول دهانات',
            'افضل مقاول بناء في جدة',
            'مقاول تشطيب في جدة',
            'مقاولين دهانات',
            'افضل مقاول بجدة',
            'هدم عماير جده',
            'مقاول في جدة ممتاز',
            'مطلوب مقاولين دهانات',
            'مقاول عام جدة',
            'مقاول كسر رخام',
            'مقاول عام',
            'معلم دهانات جدة',
            'معلم دهانات جده',
            'معلم دهان جده',
            'معلم دهان جدة',
            'معلم دهان',
            'معلم بويه',
            'معلم بوية',
            'معلم بويات جدة',
            'معلم بويا جدة',
            'معلم بوية جدة',
            'معلم دهان في جدة',
            'معلم فوم جدة',
            'معلم ديكور جدة',
            'دهانات وديكورات جدة',
            'معلم دهانات وديكورات جدة',
            'ديكور بديل الرخام',
            'ديكور بديل الخشب',
            'ديكور مرايا',
            'معلم ديكور',
            'فني ديكور',
            'فني ديكورات جدة',
        ])->filter()->unique()->implode(', ');
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

        return view('services.index', compact('services', 'meta_title', 'meta_description', 'meta_keywords', 'settings'));
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
        $service = Service::where('active', true)->where('slug', $slug)->first();

        if (! $service) {
            return redirect()->route('services.index')->with('error', 'عذراً، الخدمة المطلوبة غير موجودة.');
        }

        $projects = $service->projects()->where('active', true)->orderBy('sort_order')->paginate(9);

        // جلب 8 خدمات عشوائية
        // ملاحظة: إذا كنت في صفحة خدمة، يفضل استبعاد الخدمة الحالية باستخدام where('slug', '!=', $slug)
        $sidebarServices = Service::inRandomOrder()->limit(8)->get();
        $meta_title = $service->meta_title ?: $service->title;
        $meta_description = $service->meta_description ?: Str::limit(strip_tags($service->description ?? ''), 160);
        // $meta_keywords = $service->title;
        $meta_keywords = collect([
            $settings['site_name'] ?? null,
            'من نحن',
            'الفن الحديث ',
            'معلم دهانات جدة',
            'دهانات جدة',
            'ديكور جدة',
            'معلم بويه جدة',
            'دهانات داخلية جدة',
            'دهانات خارجية جدة',
            'سعر دهانات جدة',
            'أفضل معلم دهانات جدة',
            'ديكورات ودهانات جدة',
            'خدمات دهانات وديكور جدة',
            'ورق جدران جدة',
            'ترميمات جدة',
            'تشطيبات ودهانات جدة',

            // كلمات طويلة متعددة نوايا بحث (Long-tail)
            'سعر المتر دهان داخلي جدة',
            'أفضل دهانات داخلية للفلل جدة',
            'أعمال دهانات داخلية وجدران جدة',
            'خدمات ديكور داخلية وخارجية جدة',
            'دهانات مقاومة للرطوبة جدة',
            'ديكور بديل خشب جدة',
            'ديكور بديل رخام جدة',
            'بديل شيبورد جدة',
            'بديل حجر جدة',
            'تركيب ورق جدران ودهانات جدة',
            'دهانات فوم وبانوهات فوم جدة',

            // كلمات عامة غير محلية (خدمات + تصاميم)
            'معلم دهانات',
            'دهانات وديكورات',
            'دهانات داخلية',
            'دهانات خارجية',
            'ديكورات فوم',
            'ديكور بديل خشب',
            'ديكور بديل رخام',
            'ترميمات ودهانات',
            'تشطيبات عامة',
            'ورق جدران وديكور',
            'ألوان بويات داخلية',
            'أحدث ألوان دهانات',
            'خدمات دهانات للمنازل',
            'خدمات ديكور للمنازل',
        ])->filter()->implode(', ');

        return view('services.show', compact('service', 'sidebarServices', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'projects'));
    }
}
