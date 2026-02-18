<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Project;
use App\Services\MediaFilenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = GalleryImage::with('project')->orderBy('sort_order')->paginate(20);

        return view('admin.gallery.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::orderBy('title')->get();

        return view('admin.gallery.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $mediaFilename = app(MediaFilenameService::class);
        $imageName = $mediaFilename->uniqueWebpFilename('gallery', (string) ($validated['title'] ?? ''), 'gallery-image');

        $manager = new ImageManager(new Driver());
        $image   = $manager->read($request->file('image'));

        // تصغير
        $image->scaleDown(900);

        // حفظ WebP مضغوط
        $image->toWebp(70)->save(
            storage_path('app/public/gallery/' . $imageName)
        );

        GalleryImage::create([
            'title'      => $validated['title'],
            'project_id' => $validated['project_id'],
            'image_path' => 'gallery/' . $imageName,
            'active'     => $request->has('active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'تم إضافة الصورة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GalleryImage $gallery)
    {
        $projects = Project::orderBy('title')->get();

        return view('admin.gallery.edit', compact('gallery', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GalleryImage $gallery)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = [
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'active' => $request->has('active') ? $request->active : 0,
            'sort_order' => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $mediaFilename = app(MediaFilenameService::class);
            $imageName = $mediaFilename->uniqueWebpFilename('gallery', (string) ($validated['title'] ?? ''), 'gallery-image');


            $manager = new ImageManager(new Driver());
            $image   = $manager->read($request->file('image'));

            $image->scaleDown(900);

            $image->toWebp(70)->save(
                storage_path('app/public/gallery/' . $imageName)
            );
            // $data['image_path'] = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = 'gallery/' . $imageName;
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GalleryImage $gallery)
    {

        // التحقق من وجود الصورة
        if ($gallery->image_path && Storage::disk('public')->delete($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'تم حذف الصورة بنجاح');
    }
}
