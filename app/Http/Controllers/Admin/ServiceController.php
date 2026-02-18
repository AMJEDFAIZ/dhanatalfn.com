<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Project;
use App\Models\Keyword;
use App\Services\KeywordService;
use App\Services\MediaFilenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;


class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $projects = Project::whereNull('service_id')->get();
        $keywords = Keyword::where('active', true)->orderBy('name')->get();
        return view('admin.services.create', compact('projects', 'keywords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')],
            'description' => 'nullable|string',
            'icon' => ['nullable', 'string', 'max:255', 'regex:/^fa-[a-z0-9-]+$/i'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
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

        $data = $request->except('image', 'project_ids', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names');
        $newImagePath = null;

        if ($request->hasFile('image')) {

            $mediaFilename = app(MediaFilenameService::class);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);

            $imagename = $mediaFilename->uniqueWebpFilename('services', (string) $request->title, 'service');

            $path = storage_path("app/public/services/{$imagename}");

            $image->toWebp(70)->save($path);

            $newImagePath = "services/{$imagename}";
            $data['image_path'] = $newImagePath;
        }

        $data['active'] = $request->has('active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        try {
            $keywordService = app(KeywordService::class);
            $userId = Auth::id();

            DB::transaction(function () use ($request, $data, $keywordService, $userId) {
                $service = Service::create($data);
                if ($request->has('project_ids')) {
                    Project::whereIn('id', $request->project_ids)->update(['service_id' => $service->id]);
                }

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
                    $service,
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

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function edit(Service $service)
    {
        $projects = Project::whereNull('service_id')->orWhere('service_id', $service->id)->get();
        $service->load('keywords');
        $keywords = Keyword::where('active', true)->orderBy('name')->get();

        $metaKeywordIds = $service->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['meta', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $contentKeywordIds = $service->keywords
            ->filter(fn($k) => in_array($k->pivot->context, ['content', 'both'], true))
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $keywordPrimaryIds = $service->keywords
            ->filter(fn($k) => (bool) $k->pivot->is_primary)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $keywordWeights = $service->keywords
            ->mapWithKeys(fn($k) => [(int) $k->id => (int) $k->pivot->weight])
            ->all();

        return view('admin.services.edit', compact('service', 'projects', 'keywords', 'metaKeywordIds', 'contentKeywordIds', 'keywordPrimaryIds', 'keywordWeights'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('services', 'slug')->ignore($service->id)
            ],
            'description' => 'nullable|string',
            'icon' => ['nullable', 'string', 'max:255', 'regex:/^fa-[a-z0-9-]+$/i'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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

        $data = $request->except('image', 'meta_keyword_ids', 'meta_keyword_names', 'content_keyword_ids', 'content_keyword_names');
        $newImagePath = null;
        $oldImagePath = $service->image_path;

        if ($request->hasFile('image')) {

            $mediaFilename = app(MediaFilenameService::class);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);

            $imagename = $mediaFilename->uniqueWebpFilename('services', (string) $request->title, 'service');

            $path = storage_path("app/public/services/{$imagename}");

            $image->toWebp(70)->save($path);

            $newImagePath = "services/{$imagename}";
            $data['image_path'] = $newImagePath;
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        try {
            $keywordService = app(KeywordService::class);
            $userId = Auth::id();

            DB::transaction(function () use ($request, $service, $data, $keywordService, $userId) {
                $service->update($data);
                if ($request->has('project_ids')) {
                    $currentIds = $service->projects()->pluck('id')->map(fn($id) => (int) $id)->all();
                    $newIds = collect($request->project_ids)->map(fn($id) => (int) $id)->all();
                    $toDetach = array_diff($currentIds, $newIds);
                    $toAttach = array_diff($newIds, $currentIds);
                    if (!empty($toDetach)) {
                        Project::whereIn('id', $toDetach)->update(['service_id' => null]);
                    }
                    if (!empty($toAttach)) {
                        Project::whereIn('id', $toAttach)->update(['service_id' => $service->id]);
                    }
                } elseif ($request->has('project_ids_present')) {
                    $service->projects()->update(['service_id' => null]);
                }

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
                    $service,
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

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح');
    }
}
