@extends('admin.layouts.admin')

@section('title', 'إضافة مشروع جديد')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إضافة مشروع جديد</h1>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="service_id">الخدمة التابعة</label>
                    <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                        <option value="">اختر الخدمة...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->title }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="title">عنوان المشروع</label>
                    <input class="form-control @error('title') is-invalid @enderror" id="title" type="text" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="location">الموقع (اختياري)</label>
                    <input class="form-control" id="location" type="text" name="location" value="{{ old('location') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="scope">نطاق العمل (اختياري)</label>
                    <input class="form-control" id="scope" type="text" name="scope" value="{{ old('scope') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="slug">السلاج (عنوان الرابط)</label>
                    <input class="form-control @error('slug') is-invalid @enderror" id="slug" type="text" name="slug" value="{{ old('slug') }}" placeholder="مثال: project-title">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-1">اتركه فارغاً ليتم توليده تلقائياً من العنوان.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="duration">المدة الزمنية (اختياري)</label>
                    <input class="form-control" id="duration" type="text" name="duration" value="{{ old('duration') }}">
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label" for="description">وصف المشروع</label>
                <textarea class="rich-text form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="main_image">الصورة الرئيسية (مطلوبة)</label>
                <input class="form-control @error('main_image') is-invalid @enderror" id="main_image" type="file" name="main_image" required>
                @error('main_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label" for="sort_order">الترتيب</label>
                    <input class="form-control" id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">نشط</label>
                    </div>
                </div>
            </div>

            <hr class="my-3">
            <h3 class="h6 fw-bold mb-3">تحسين محركات البحث (SEO)</h3>

            <div class="mb-3">
                <label class="form-label" for="meta_title">عنوان الميتا (Meta Title)</label>
                <input class="form-control" id="meta_title" type="text" name="meta_title" value="{{ old('meta_title') }}">
                <small class="text-muted d-block mt-1">يترك فارغاً لاستخدام العنوان الافتراضي.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="meta_description">وصف الميتا (Meta Description)</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                <small class="text-muted d-block mt-1">وصف مختصر يظهر في نتائج البحث.</small>
            </div>

            <div class="text-end">
                <button class="btn btn-primary" type="submit">حفظ المشروع</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
