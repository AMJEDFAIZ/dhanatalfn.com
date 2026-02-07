@extends('admin.layouts.admin')

@section('title', 'إدارة الخدمات')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إدارة الخدمات</h1>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">إضافة خدمة جديدة</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-end">العنوان</th>
                    <th class="text-end">الصورة / الأيقونة</th>
                    <th class="text-center">الحالة</th>
                    <th class="text-center">الترتيب</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td class="text-end">
                        <span class="fw-medium">{{ $service->title }}</span>
                    </td>
                    <td class="text-end">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" class="rounded-circle object-cover" style="width:40px;height:40px">
                        @elseif($service->icon)
                            <i class="{{ $service->icon }} fs-4"></i>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($service->active)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-danger">غير نشط</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $service->sort_order }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                تعديل
                            </a>
                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline">
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
        {{ $services->links() }}
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
