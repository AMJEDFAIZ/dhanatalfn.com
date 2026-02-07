<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Project;
use Illuminate\Http\Request;
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
        return view('admin.services.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id'
        ]);

        $data = $request->except('image', 'project_ids');
        $newImagePath = null;

        if ($request->hasFile('image')) {

            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);

            $imagename = $tempslug . "-" . time() . ".webp";

            $path = storage_path("app/public/services/{$imagename}");

            $image->toWebp(70)->save($path);

            $newImagePath = "services/{$imagename}";
            $data['image_path'] = $newImagePath;
        }

        $data['active'] = $request->has('active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        try {
            DB::transaction(function () use ($request, $data) {
                $service = Service::create($data);
                if ($request->has('project_ids')) {
                    Project::whereIn('id', $request->project_ids)->update(['service_id' => $service->id]);
                }
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
        return view('admin.services.edit', compact('service', 'projects'));
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
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->except('image');
        $newImagePath = null;
        $oldImagePath = $service->image_path;

        if ($request->hasFile('image')) {

            $tempslug = Str::slug($request->title);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scaleDown(900);

            $imagename = $tempslug . "-" . time() . ".webp";

            $path = storage_path("app/public/services/{$imagename}");

            $image->toWebp(70)->save($path);

            $newImagePath = "services/{$imagename}";
            $data['image_path'] = $newImagePath;
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        try {
            DB::transaction(function () use ($request, $service, $data) {
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
