@extends('admin.layouts.admin')

@section('title', 'إدارة المدونة')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إدارة المدونة</h1>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">إضافة مقال جديد</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-end">عنوان المقال</th>
                        <th class="text-end">الصورة</th>
                        <th class="text-center">تاريخ النشر</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td class="text-end">
                            <span class="fw-medium">{{ $post->title }}</span>
                        </td>
                        <td class="text-end">
                            @if($post->image_path)
                                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="rounded-circle object-cover" style="width:40px;height:40px">
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $post->published_at ? $post->published_at->format('Y-m-d') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($post->active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $posts->links() }}
    </div>
</div>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
