<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
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
        return view('admin.blog.create');
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
        ]);

        $data = $request->except('image');

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

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'تم إضافة المقال بنجاح');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', compact('blog'));
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
        ]);

        $data = $request->except('image');

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