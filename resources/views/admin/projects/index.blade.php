@extends('admin.layouts.admin')

@section('title', 'إدارة المشاريع')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إدارة المشاريع</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">إضافة مشروع جديد</a>
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
                        <th class="text-end">عنوان المشروع</th>
                        <th class="text-end">الصورة الرئيسية</th>
                        <th class="text-end">الموقع</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">الترتيب</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td class="text-end">
                            <span class="fw-medium">{{ $project->title }}</span>
                        </td>
                        <td class="text-end">
                            @if($project->main_image)
                                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}" class="rounded-circle object-cover" style="width:40px;height:40px">
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="text-end">{{ $project->location ?? '-' }}</td>
                        <td class="text-center">
                            @if($project->active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $project->sort_order }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline">
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
        {{ $projects->links() }}
    </div>
</div>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
