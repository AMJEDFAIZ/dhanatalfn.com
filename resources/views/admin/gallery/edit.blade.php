@extends('admin.layouts.admin')

@section('title', 'تعديل الصورة')
@section('content')
    <div class="container-fluid py-3">
        <div class="card p-3">
                    <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- Title -->
                            <div>
                                <label for="title" class="form-label">العنوان (اختياري)</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Project -->
                            <div>
                                <label for="project_id" class="form-label">مرتبط بمشروع (اختياري)</label>
                                <select name="project_id" id="project_id" class="form-select">
                                    <option value="">-- اختر المشروع --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $gallery->project_id) == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                    @endforeach
                                </select>
                                @error('project_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="form-label">الترتيب</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" class="form-control">
                            </div>

                            <!-- Image -->
                            <div>
                                <label for="image" class="form-label">الصورة</label>
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Current Image" class="rounded border" style="height:64px;width:64px;object-fit:cover">
                                </div>
                                <input type="file" name="image" id="image" class="form-control">
                                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', $gallery->active) ? 'checked' : '' }} class="form-check-input">
                            <label for="active" class="form-check-label">نشط (يظهر في الموقع)</label>
                        </div>

                        <div class="mt-3 text-end">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </div>
                    </form>

    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
