<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\SeoPage;
use App\Services\KeywordService;
use App\Services\SeoPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeoPageController extends Controller
{
    public function index(SeoPageService $seoPageService)
    {
        $seoPageService->ensureDefaults();

        $pages = SeoPage::orderBy('name')->get();

        return view('admin.seo-pages.index', compact('pages'));
    }

    public function edit(SeoPage $seoPage, SeoPageService $seoPageService)
    {
        $seoPageService->ensureDefaults();

        $seoPage->load('keywords');
        $keywords = Keyword::where('active', true)->orderBy('name')->get();

        $metaKeywordIds = $seoPage->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['meta', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $contentKeywordIds = $seoPage->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['content', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        return view('admin.seo-pages.edit', compact('seoPage', 'keywords', 'metaKeywordIds', 'contentKeywordIds'));
    }

    public function update(Request $request, SeoPage $seoPage, KeywordService $keywordService)
    {
        $validated = $request->validate([
            'meta_keyword_ids' => ['nullable', 'array'],
            'meta_keyword_ids.*' => ['integer', 'exists:keywords,id'],
            'meta_keyword_names' => ['nullable', 'string'],
            'content_keyword_ids' => ['nullable', 'array'],
            'content_keyword_ids.*' => ['integer', 'exists:keywords,id'],
            'content_keyword_names' => ['nullable', 'string'],
        ]);

        $userId = Auth::id();
        $metaIds = $keywordService->resolveIdsOrFail(
            $validated['meta_keyword_ids'] ?? [],
            $validated['meta_keyword_names'] ?? null,
            KeywordService::META_LIMIT,
            'meta_keyword_ids',
            'ar',
            $userId,
        );

        $contentIds = $keywordService->resolveIdsOrFail(
            $validated['content_keyword_ids'] ?? [],
            $validated['content_keyword_names'] ?? null,
            KeywordService::CONTENT_LIMIT,
            'content_keyword_ids',
            'ar',
            $userId,
        );

        $keywordService->syncContexts($seoPage, $metaIds, $contentIds);

        return redirect()->route('admin.seo-pages.index')->with('success', 'تم تحديث كلمات الصفحة بنجاح');
    }
}
