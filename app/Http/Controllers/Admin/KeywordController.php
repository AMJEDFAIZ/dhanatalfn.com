<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Services\KeywordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $query = Keyword::query()->withCount(['services', 'projects', 'blogPosts', 'seoPages']);
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $keywords = $query->orderByDesc('active')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.keywords.index', compact('keywords', 'search'));
    }

    public function create()
    {
        return view('admin.keywords.create');
    }

    public function store(Request $request, KeywordService $keywordService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'locale' => ['nullable', 'string', 'max:10'],
            'active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $locale = $validated['locale'] ?? 'ar';
        $prepared = $keywordService->prepareForSave($validated['name'], $locale);

        if ($prepared['name'] === '' || $prepared['normalized'] === '') {
            return back()->withErrors(['name' => 'الكلمة المفتاحية غير صالحة.'])->withInput();
        }

        $exists = Keyword::query()
            ->where('locale', $locale)
            ->where('normalized', $prepared['normalized'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'هذه الكلمة موجودة مسبقاً.'])->withInput();
        }

        $userId = Auth::id();
        Keyword::create([
            'name' => $prepared['name'],
            'slug' => $prepared['slug'],
            'normalized' => $prepared['normalized'],
            'locale' => $prepared['locale'],
            'active' => (bool) ($validated['active'] ?? true),
            'description' => $validated['description'] ?? null,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return redirect()->route('admin.keywords.index')->with('success', 'تم إضافة الكلمة المفتاحية بنجاح');
    }

    public function show(Keyword $keyword)
    {
        $keyword->loadCount(['services', 'projects', 'blogPosts', 'seoPages']);

        $services = $keyword->services()->where('active', true)->orderBy('title')->limit(30)->get();
        $projects = $keyword->projects()->where('active', true)->orderBy('title')->limit(30)->get();
        $blogPosts = $keyword->blogPosts()->where('active', true)->orderByDesc('published_at')->limit(30)->get();
        $seoPages = $keyword->seoPages()->orderBy('name')->get();

        return view('admin.keywords.show', compact('keyword', 'services', 'projects', 'blogPosts', 'seoPages'));
    }

    public function edit(Keyword $keyword)
    {
        return view('admin.keywords.edit', compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword, KeywordService $keywordService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'locale' => ['nullable', 'string', 'max:10'],
            'active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $locale = $validated['locale'] ?? $keyword->locale ?? 'ar';
        $prepared = $keywordService->prepareForSave($validated['name'], $locale, $keyword->id);

        if ($prepared['name'] === '' || $prepared['normalized'] === '') {
            return back()->withErrors(['name' => 'الكلمة المفتاحية غير صالحة.'])->withInput();
        }

        $exists = Keyword::query()
            ->where('locale', $locale)
            ->where('normalized', $prepared['normalized'])
            ->where('id', '!=', $keyword->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'هذه الكلمة موجودة مسبقاً.'])->withInput();
        }

        $keyword->update([
            'name' => $prepared['name'],
            'slug' => $prepared['slug'],
            'normalized' => $prepared['normalized'],
            'locale' => $prepared['locale'],
            'active' => (bool) ($validated['active'] ?? false),
            'description' => $validated['description'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.keywords.index')->with('success', 'تم تحديث الكلمة المفتاحية بنجاح');
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->loadCount(['services', 'projects', 'blogPosts', 'seoPages']);
        $usage = (int) $keyword->services_count + (int) $keyword->projects_count + (int) $keyword->blog_posts_count + (int) $keyword->seo_pages_count;

        if ($usage > 0) {
            return redirect()
                ->route('admin.keywords.index')
                ->with('error', 'لا يمكن حذف كلمة مستخدمة. قم بتعطيلها بدلاً من ذلك.');
        }

        $keyword->delete();

        return redirect()->route('admin.keywords.index')->with('success', 'تم حذف الكلمة المفتاحية بنجاح');
    }
}
