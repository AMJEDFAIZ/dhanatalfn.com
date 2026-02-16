<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use App\Services\SeoMetaService;
use App\Services\SeoPageService;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $projects = Project::where('active', true)->orderBy('sort_order')->paginate(12);
        $meta_title = Setting::where('key', 'projects_meta_title')->value('value') ?? 'المشاريع';
        $meta_description = Setting::where('key', 'projects_meta_description')->value('value') ?? 'تصفح أحدث مشاريعنا المنفذة بجودة واحترافية.';
        $seoPage = app(SeoPageService::class)->getByKey('projects_index');
        $meta_keywords = app(SeoMetaService::class)->keywordsString($seoPage?->keywordsForMeta() ?? []);
        $pageContentKeywords = $seoPage?->contentKeywords()->where('keywords.active', true)->orderBy('keywords.name')->get() ?? collect();

        return view('projects.index', compact('projects', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'pageContentKeywords'));
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
        $meta = app(SeoMetaService::class)->metaForModel($project, [
            'fallback_keywords' => array_filter([$settings['site_name'] ?? null, $project->title]),
        ]);
        $meta_title = $meta['meta_title'];
        $meta_description = $meta['meta_description'] ?: Str::limit(strip_tags($project->description ?? ''), 160);
        $meta_keywords = $meta['meta_keywords'];
        $contentKeywords = $project->contentKeywords()->where('keywords.active', true)->orderBy('keywords.name')->get();

        return view('projects.show', compact('project', 'projectImages', 'meta_title', 'meta_description', 'meta_keywords', 'settings', 'sidebarProjects', 'contentKeywords'));
    }
}
