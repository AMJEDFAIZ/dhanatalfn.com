<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\MediaFilenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->paginate(10);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {

            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $mediaFilename = app(MediaFilenameService::class);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image_path'));
            $image->scaleDown(900);
            $imagename = $mediaFilename->uniqueWebpFilename('testimonials', (string) $request->client_name, 'testimonial');

            $image->toWebp(70)->save(storage_path("app/public/testimonials/" . $imagename));



            $data['image_path'] = 'testimonials/' . $imagename;
            // $data['image_path'] = $request->file('image_path')->store('testimonials', 'public');
        }

        $data['active'] = $request->has('active') ? $request->active : 1;
        $data['sort_order'] = $request->sort_order ?? 0;

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تم إضافة الرأي بنجاح');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            if ($testimonial->image_path) {
                Storage::disk('public')->delete($testimonial->image_path);
            }

            // عمل سلاج مؤقت لتسمية الصورة بحسب العنوان
            $mediaFilename = app(MediaFilenameService::class);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image_path'));
            $image->scaleDown(900);
            $imagename = $mediaFilename->uniqueWebpFilename('testimonials', (string) $request->client_name, 'testimonial');

            $image->toWebp(70)->save(storage_path("app/public/testimonials/" . $imagename));



            $data['image_path'] = 'testimonials/' . $imagename;
            // $data['image_path'] = $request->file('image_path')->store('testimonials', 'public');
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تم تحديث الرأي بنجاح');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image_path && Storage::disk('public')->delete($testimonial->image_path)) {
            Storage::disk('public')->delete($testimonial->image_path);
        }
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'تم حذف الرأي بنجاح');
    }
}
