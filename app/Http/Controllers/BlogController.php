<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Setting;
use App\Services\SeoMetaService;
use App\Services\SeoPageService;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $search = request()->string('search')->trim()->toString();

        $postsQuery = BlogPost::where('active', true)->orderBy('published_at', 'desc');

        if ($search !== '') {
            $postsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $posts = $postsQuery->paginate(9)->withQueryString();
        $meta_title = Setting::where('key', 'blog_meta_title')->value('value') ?? 'المدونة';
        $meta_description = Setting::where('key', 'blog_meta_description')->value('value') ?? 'اقرأ أحدث المقالات والنصائح في مجال المقاولات والبناء.';
        $seoPage = app(SeoPageService::class)->getByKey('blog_index');
        $meta_keywords = app(SeoMetaService::class)->keywordsString($seoPage?->keywordsForMeta() ?? []);
        if ($search !== '') {
            $meta_title = $search . ' - ' . $meta_title;
            $meta_description = $search . ' - ' . $meta_description;
            if ($meta_keywords !== '') {
                $meta_keywords = $meta_keywords . ', ' . $search;
            } else {
                $meta_keywords = $search;
            }
        }
        $pageContentKeywords = $seoPage?->contentKeywords()->where('keywords.active', true)->get() ?? collect();

        return view('blog.index', compact('posts', 'meta_title', 'meta_description', 'meta_keywords', 'search', 'pageContentKeywords'));
    }

    public function show($slug)
    {
        $post = BlogPost::with('faqs')->where('active', true)->where('slug', $slug)->first();
        if (! $post) {
            return redirect()->route('blog.index')->with('error', 'عذراً، المقال المطلوب غير موجود.');
        }
        // جلب آخر 5 مقالات (باستثناء المقال الحالي)
        $recentPosts = BlogPost::where('active', true) //شرط النشاط
            ->where('slug', '!=', $slug)
            ->latest()
            ->take(15)
            ->get();
        /*
            // جلب المقال السابق (الأصغر معرفاً)
    $prevPost = BlogPost::where('slug', '<', $slug)->orderBy('slug', 'desc')->first();

    // جلب المقال التالي (الأكبر معرفاً)
    $nextPost = BlogPost::where('slug', '>', $slug)->orderBy('slug', 'asc')->first(); */

        // نستخدم ID للمقارنة الزمنية، ونضيف شرط active
        $prevPost = BlogPost::where('active', true)
            ->where('id', '<', $post->id) // نستخدم ID بدلاً من slug للترتيب الصحيح
            ->orderBy('id', 'desc')
            ->first();

        // 4. جلب المقال التالي (الأقدم بعده مباشرة)
        $nextPost = BlogPost::where('active', true)
            ->where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->first();


        $meta_title = $post->meta_title ?: $post->title;
        $meta_description = $post->meta_description ?: Str::limit(strip_tags($post->content ?? ''), 160);
        $meta = app(SeoMetaService::class)->metaForModel($post, [
            'fallback_keywords' => [$post->title],
        ]);
        $meta_keywords = $meta['meta_keywords'];
        $contentKeywords = $post->contentKeywords()->where('keywords.active', true)->get();

        return view('blog.show', compact('post', 'prevPost', 'nextPost', 'recentPosts', 'meta_title', 'meta_description', 'meta_keywords', 'contentKeywords'));
    }
}
