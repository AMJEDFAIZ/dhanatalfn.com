@extends('admin.layouts.admin')

@section('title', 'معرض الصور')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">+ إضافة صورة جديدة</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="row g-3">
            @forelse($images as $image)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}" class="card-img-top" style="height:180px;object-fit:cover">
                        <div class="card-body">
                            <h6 class="card-title mb-1">{{ $image->title ?: 'بدون عنوان' }}</h6>
                            @if($image->project)
                                <div class="small text-muted mb-2">المشروع: {{ $image->project->title }}</div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $image->active ? 'bg-success' : 'bg-danger' }}">{{ $image->active ? 'نشط' : 'غير نشط' }}</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.gallery.edit', $image) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                    <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">لا توجد صور مضافة حالياً.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $images->links() }}
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
