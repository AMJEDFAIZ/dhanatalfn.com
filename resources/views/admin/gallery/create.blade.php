@extends('admin.layouts.admin')

@section('title', 'إضافة صورة للمعرض')
@section('content')
    <div class="container-fluid py-3">
        <div class="card p-3">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- Title -->
                            <div>
                                <label for="title" class="form-label">العنوان (اختياري)</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Project -->
                            <div>
                                <label for="project_id" class="form-label">مرتبط بمشروع (اختياري)</label>
                                <select name="project_id" id="project_id" class="form-select">
                                    <option value="">-- اختر المشروع --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                    @endforeach
                                </select>
                                @error('project_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="form-label">الترتيب</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                            </div>

                            <!-- Image -->
                            <div>
                                <label for="image" class="form-label">الصورة</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="form-check mt-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', 1) ? 'checked' : '' }} class="form-check-input">
                            <label for="active" class="form-check-label">نشط (يظهر في الموقع)</label>
                        </div>

                        <div class="mt-3 text-end">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>

    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
