<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Keyword;
use App\Services\KeywordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('sort_order')->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $services = Service::where('active', true)->orderBy('title')->get();
        $keywords = Keyword::where('active', true)->orderBy('name')->get();
        return view('admin.projects.create', compact('services', 'keywords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')],
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'scope' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keyword_ids' => 'nullable|array',
            'meta_keyword_ids.*' => 'integer|exists:keywords,id',
            'meta_keyword_names' => 'nullable|string',
            'content_keyword_ids' => 'nullable|array',
            'content_keyword_ids.*' => 'integer|exists:keywords,id',
            'content_keyword_names' => 'nullable|string',
            'keyword_primary_ids' => 'nullable|array',
            'keyword_primary_ids.*' => 'integer|exists:keywords,id',
            'keyword_weights' => 'nullable|array',
            'keyword_weights.*' => 'nullable|integer|min:0|max:65535',
        ]);

        $data = $request->except('main_image', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names');
        $newImagePath = null;


        if ($request->hasFile('main_image')) {


            // $slug =$request->slug?$request->slug: Str::slug($request->title) ;
            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('main_image'));
            $image->scaleDown(900);
            $imagename = $tempslug . "-" . time() . ".webp";

            $image->toWebp(70)->save(storage_path("app/public/projects/" . $imagename));


            // $data['main_image'] = $request->file('main_image')->store('projects', 'public');
            $newImagePath = 'projects/' . $imagename;
            $data['main_image'] = $newImagePath;
        }

        // $data['active'] = $request->has('active') ? $request->active : 1;
        $data['active'] = $request->has('active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        try {
            $keywordService = app(KeywordService::class);
            $userId = Auth::id();

            DB::transaction(function () use ($data, $request, $keywordService, $userId) {
                $project = Project::create($data);

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
                $keywordService->syncContexts(
                    $project,
                    $metaIds,
                    $contentIds,
                    $request->input('keyword_primary_ids', []),
                    $request->input('keyword_weights', [])
                );
            });
        } catch (\Throwable $e) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }
            throw $e;
        }

        return redirect()->route('admin.projects.index')->with('success', 'تم إضافة المشروع بنجاح');
    }

    public function edit(Project $project)
    {
        $services = Service::where('active', true)->orderBy('title')->get();
        $project->load('keywords');
        $keywords = Keyword::where('active', true)->orderBy('name')->get();

        $metaKeywordIds = $project->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['meta', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $contentKeywordIds = $project->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['content', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $keywordPrimaryIds = $project->keywords
            ->filter(fn($k) => (bool) $k->pivot->is_primary)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $keywordWeights = $project->keywords
            ->mapWithKeys(fn($k) => [(int) $k->id => (int) $k->pivot->weight])
            ->all();

        return view('admin.projects.edit', compact('project', 'services', 'keywords', 'metaKeywordIds', 'contentKeywordIds', 'keywordPrimaryIds', 'keywordWeights'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($project->id)],
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'scope' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keyword_ids' => 'nullable|array',
            'meta_keyword_ids.*' => 'integer|exists:keywords,id',
            'meta_keyword_names' => 'nullable|string',
            'content_keyword_ids' => 'nullable|array',
            'content_keyword_ids.*' => 'integer|exists:keywords,id',
            'content_keyword_names' => 'nullable|string',
            'keyword_primary_ids' => 'nullable|array',
            'keyword_primary_ids.*' => 'integer|exists:keywords,id',
            'keyword_weights' => 'nullable|array',
            'keyword_weights.*' => 'nullable|integer|min:0|max:65535',
        ]);

        $data = $request->except('main_image', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names');
        $newImagePath = null;
        $oldImagePath = $project->main_image;

        if ($request->hasFile('main_image')) {
            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('main_image'));
            $image->scaleDown(900);
            $imagename = $tempslug . "-" . time() . ".webp";

            $image->toWebp(70)->save(storage_path("app/public/projects/" . $imagename));


            // $data['main_image'] = $request->file('main_image')->store('projects', 'public');
            $newImagePath = 'projects/' . $imagename;
            $data['main_image'] = $newImagePath;
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        try {
            $keywordService = app(KeywordService::class);
            $userId = Auth::id();

            DB::transaction(function () use ($project, $data, $request, $keywordService, $userId) {
                $project->update($data);

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
                $keywordService->syncContexts(
                    $project,
                    $metaIds,
                    $contentIds,
                    $request->input('keyword_primary_ids', []),
                    $request->input('keyword_weights', [])
                );
            });
        } catch (\Throwable $e) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }
            throw $e;
        }

        if ($newImagePath && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('admin.projects.index')->with('success', 'تم تحديث المشروع بنجاح');
    }

    public function destroy(Project $project)
    {
        if ($project->main_image) {
            Storage::disk('public')->delete($project->main_image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'تم حذف المشروع بنجاح');
    }
}
