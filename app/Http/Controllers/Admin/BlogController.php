<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Keyword;
use App\Services\KeywordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $keywords = Keyword::where('active', true)->orderBy('name')->get();
        return view('admin.blog.create', compact('keywords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')],
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string',
            'faqs.*.answer' => 'nullable|string',
            'meta_keyword_ids' => 'nullable|array',
            'meta_keyword_ids.*' => 'integer|exists:keywords,id',
            'meta_keyword_names' => 'nullable|string',
            'content_keyword_ids' => 'nullable|array',
            'content_keyword_ids.*' => 'integer|exists:keywords,id',
            'content_keyword_names' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'faqs', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names']);

        if ($request->hasFile('image')) {
            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);
            $imagename = $tempslug . "-" . time() . ".webp";

            $image->toWebp(70)->save(storage_path("app/public/blog/" . $imagename));



            $data['image_path'] = 'blog/' . $imagename;
            // $data['image_path'] = $request->file('image')->store('blog', 'public');
        }

        $data['active'] = $request->has('active') ? $request->active : 1;

        $keywordService = app(KeywordService::class);
        $userId = Auth::id();

        $post = BlogPost::create($data);

        $metaIds = $keywordService->resolveIdsOrFail(
            $request->input('meta_keyword_ids', []),
            $request->input('meta_keyword_names'),
            KeywordService::META_LIMIT,
            'meta_keyword_ids',
            'ar',
            $userId
        );
        $contentIds = $keywordService->resolveIdsOrFail(
            $request->input('content_keyword_ids', []),
            $request->input('content_keyword_names'),
            KeywordService::CONTENT_LIMIT,
            'content_keyword_ids',
            'ar',
            $userId
        );
        $keywordService->syncContexts($post, $metaIds, $contentIds);

        // Handle FAQs
        if ($request->has('faqs')) {
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $post->faqs()->create([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'active' => true
                    ]);
                }
            }
        }

        return redirect()->route('admin.blog.index')->with('success', 'تم إضافة المقال بنجاح');
    }

    public function edit(BlogPost $blog)
    {
        $blog->load('faqs');
        $blog->load('keywords');
        $keywords = Keyword::where('active', true)->orderBy('name')->get();

        $metaKeywordIds = $blog->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['meta', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $contentKeywordIds = $blog->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['content', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        return view('admin.blog.edit', compact('blog', 'keywords', 'metaKeywordIds', 'contentKeywordIds'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($blog->id)],
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string',
            'faqs.*.answer' => 'nullable|string',
            'meta_keyword_ids' => 'nullable|array',
            'meta_keyword_ids.*' => 'integer|exists:keywords,id',
            'meta_keyword_names' => 'nullable|string',
            'content_keyword_ids' => 'nullable|array',
            'content_keyword_ids.*' => 'integer|exists:keywords,id',
            'content_keyword_names' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'faqs', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names']);

        if ($request->hasFile('image')) {
            if ($blog->image_path) {
                Storage::disk('public')->delete($blog->image_path);
            }
            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);
            $imagename = $tempslug . "-" . time() . ".webp";

            $image->toWebp(70)->save(storage_path("app/public/blog/" . $imagename));



            $data['image_path'] = 'blog/' . $imagename;
            // $data['image_path'] = $request->file('image')->store('blog', 'public');
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        $blog->update($data);

        $keywordService = app(KeywordService::class);
        $userId = Auth::id();
        $metaIds = $keywordService->resolveIdsOrFail(
            $request->input('meta_keyword_ids', []),
            $request->input('meta_keyword_names'),
            KeywordService::META_LIMIT,
            'meta_keyword_ids',
            'ar',
            $userId
        );
        $contentIds = $keywordService->resolveIdsOrFail(
            $request->input('content_keyword_ids', []),
            $request->input('content_keyword_names'),
            KeywordService::CONTENT_LIMIT,
            'content_keyword_ids',
            'ar',
            $userId
        );
        $keywordService->syncContexts($blog, $metaIds, $contentIds);

        // Handle FAQs
        if ($request->has('faqs')) {
            $blog->faqs()->delete(); // Reset FAQs
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $blog->faqs()->create([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'active' => true
                    ]);
                }
            }
        } elseif ($request->exists('faqs_submit_indicator')) {
            // If the indicator exists but faqs array is empty, it means user deleted all FAQs
            $blog->faqs()->delete();
        }

        return redirect()->route('admin.blog.index')->with('success', 'تم تحديث المقال بنجاح');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->image_path &&  Storage::disk('public')->delete($blog->image_path)) {
            Storage::disk('public')->delete($blog->image_path);
        }
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'تم حذف المقال بنجاح');
    }
}
