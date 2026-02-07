<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
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
        return view('admin.projects.create', compact('services'));
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
        ]);

        $data = $request->except('main_image');
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
            DB::transaction(function () use ($data) {
                Project::create($data);
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
        return view('admin.projects.edit', compact('project', 'services'));
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
        ]);

        $data = $request->except('main_image');
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
            DB::transaction(function () use ($project, $data) {
                $project->update($data);
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
