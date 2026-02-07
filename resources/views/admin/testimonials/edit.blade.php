@extends('admin.layouts.admin')

@section('title', 'تعديل الرأي')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h6 fw-bold mb-0">تعديل الرأي</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="client_name" class="form-label">اسم العميل</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $testimonial->client_name) }}" class="form-control" required>
                    @error('client_name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="position" class="form-label">المسمى الوظيفي (اختياري)</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $testimonial->position) }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="rating" class="form-label">التقييم (من 5)</label>
                    <select name="rating" id="rating" class="form-select">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 نجوم</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 نجوم</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 نجوم</option>
                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 نجوم</option>
                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>نجمة واحدة</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="sort_order" class="form-label">الترتيب</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="image" class="form-label">صورة العميل</label>
                    @if($testimonial->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Current Image" class="rounded border" style="height:80px;width:80px;object-fit:cover">
                        </div>
                    @endif
                    <input type="file" name="image_path" id="image" class="form-control">
                    @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-check mt-3">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', $testimonial->active) ? 'checked' : '' }} class="form-check-input">
                <label for="active" class="form-check-label">نشط (يظهر في الموقع)</label>
            </div>

            <div class="mt-3">
                <label for="content" class="form-label">نص الرأي</label>
                <textarea name="content" id="content" rows="4" class="form-control" required>{{ old('content', $testimonial->content) }}</textarea>
                @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary me-2">إلغاء</a>
                <button type="submit" class="btn btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endsection
