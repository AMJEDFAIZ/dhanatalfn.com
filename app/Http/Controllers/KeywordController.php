<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function show(Request $request, Keyword $keyword)
    {
        if (! $keyword->active) {
            abort(404);
        }

        $keyword->loadCount(['services', 'projects', 'blogPosts', 'seoPages']);
        $usage = (int) $keyword->services_count + (int) $keyword->projects_count + (int) $keyword->blog_posts_count + (int) $keyword->seo_pages_count;
        $robots_noindex = $usage < 2 && empty($keyword->description);

        $type = $request->string('type')->trim()->toString();
        $robots_noindex = $robots_noindex || $type !== '';
        $items = null;
        $services = collect();
        $projects = collect();
        $posts = collect();

        if ($type === 'services') {
            $items = $keyword->services()->where('active', true)->orderBy('title')->paginate(12)->withQueryString();
        } elseif ($type === 'projects') {
            $items = $keyword->projects()->where('active', true)->orderBy('title')->paginate(12)->withQueryString();
        } elseif ($type === 'blog') {
            $items = $keyword->blogPosts()->where('active', true)->orderByDesc('published_at')->paginate(12)->withQueryString();
        } else {
            $services = $keyword->services()->where('active', true)->orderBy('title')->limit(12)->get();
            $projects = $keyword->projects()->where('active', true)->orderBy('title')->limit(12)->get();
            $posts = $keyword->blogPosts()->where('active', true)->orderByDesc('published_at')->limit(12)->get();
        }

        $meta_title = $keyword->name;
        $meta_description = $keyword->description ?: ('كل ما يتعلق بـ ' . $keyword->name . ' من خدمات ومشاريع ومقالات.');
        $meta_keywords = $keyword->name;

        return view('keywords.show', compact(
            'keyword',
            'type',
            'items',
            'services',
            'projects',
            'posts',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'robots_noindex'
        ));
    }
}
