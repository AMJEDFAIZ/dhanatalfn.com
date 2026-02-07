@extends('admin.layouts.admin')

@section('title', 'تعديل المقال')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">تعديل المقال: {{ $blog->title }}</h1>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label" for="title">عنوان المقال</label>
                <input class="form-control @error('title') is-invalid @enderror" id="title" type="text" name="title" value="{{ old('title', $blog->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="content">المحتوى</label>
                <textarea class="rich-text form-control" id="content" name="content" rows="10" required>{{ old('content', $blog->content) }}</textarea>
                @error('content')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="slug">السلاج (عنوان الرابط)</label>
                <input class="form-control @error('slug') is-invalid @enderror" id="slug" type="text" name="slug" value="{{ old('slug', $blog->slug) }}" placeholder="مثال: blog-post-title">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">اتركه فارغاً ليتم توليده تلقائياً من العنوان.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="image">صورة المقال (اتركها فارغة للإبقاء على الصورة الحالية)</label>
                @if($blog->image_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="rounded border" style="height:128px;width:128px;object-fit:cover">
                    </div>
                @endif
                <input class="form-control" id="image" type="file" name="image">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label" for="published_at">تاريخ النشر</label>
                    <input class="form-control" id="published_at" type="date" name="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d') : '') }}">
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="active" id="active" value="1" {{ old('active', $blog->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">نشط</label>
                    </div>
                </div>
            </div>

            <hr class="my-3">
            <h3 class="h6 fw-bold mb-3">تحسين محركات البحث (SEO)</h3>

            <div class="mb-3">
                <label class="form-label" for="meta_title">عنوان الميتا (Meta Title)</label>
                <input class="form-control" id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}">
                <small class="text-muted d-block mt-1">يترك فارغاً لاستخدام العنوان الافتراضي.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="meta_description">وصف الميتا (Meta Description)</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                <small class="text-muted d-block mt-1">وصف مختصر يظهر في نتائج البحث.</small>
            </div>

            <div class="text-end">
                <button class="btn btn-primary" type="submit">تحديث المقال</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
