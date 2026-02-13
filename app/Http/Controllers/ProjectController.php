<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $projects = Project::where('active', true)->orderBy('sort_order')->paginate(12);
        $meta_title = Setting::where('key', 'projects_meta_title')->value('value') ?? 'المشاريع';
        $meta_description = Setting::where('key', 'projects_meta_description')->value('value') ?? 'تصفح أحدث مشاريعنا المنفذة بجودة واحترافية.';
        // $meta_keywords = collect($projects->pluck('title')->take(5))->implode(', ');
        $meta_keywords = collect($projects->pluck('title')->take(5))->merge([
            $settings['site_name'] ?? null,
            'من نحن',
            'مقاول عام جدة',
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
            'ديكور بديل الرخام',
            'ديكور بديل الخشب',
            'ديكور مرايا',
            'معلم ديكور',
            'فني ديكور',
            'فني ديكورات جدة',
            'دهانات داخلية جدة',
            'دهانات خارجية جدة',
            'سعر دهانات جدة',
            'أفضل معلم دهانات جدة',
            'ديكورات ودهانات جدة',
            'خدمات دهانات وديكور جدة',
            'ورق جدران جدة',
            'ترميمات جدة',
            'تشطيبات ودهانات جدة',

           
        ])->filter()->unique()->implode(', ');

        return view('projects.index', compact('projects', 'meta_title', 'meta_description', 'meta_keywords', 'settings'));
    }

    public function show($slug)
    {
        $settings = Setting::all()->pluck('value', 'key');
        $project = Project::with('images')->where('active', true)->where('slug', $slug)->first();
        if (! $project) {
            return redirect()->route('projects.index')->with('error', 'عذراً، المشروع المطلوب غير موجود.');
        }
        $projectImages = $project->images()->paginate(6);
        // جلب 8 خدمات عشوائية
        // ملاحظة: إذا كنت في صفحة خدمة، يفضل استبعاد الخدمة الحالية باستخدام where('slug', '!=', $slug)
        $sidebarProjects = Project::inRandomOrder()->limit(8)->get();
        $meta_title = $project->meta_title ?: $project->title;
        $meta_description = $project->meta_description ?: Str::limit(strip_tags($project->description ?? ''), 160);
        // $meta_keywords = $project->title;
        $meta_keywords = collect([
            $project->title,
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
        ])->filter()->unique()->implode(', ');

        return view('projects.show', compact('project', 'projectImages', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'sidebarProjects'));
    }
}
