@extends('admin.layouts.admin')

@section('title', 'المهارات')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">+ إضافة مهارة جديدة</a>
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
                        <th class="text-end">#</th>
                        <th class="text-end">المهارة</th>
                        <th class="text-end">النسبة</th>
                        <th class="text-end">الحالة</th>
                        <th class="text-end">الترتيب</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skills as $skill)
                        <tr>
                            <td class="text-end">{{ $loop->iteration }}</td> {{-- لعمل ترقيم تلقائي للعناصر في الجدول  --}}
                            <td class="text-end">{{ $skill->name }}</td>
                            
                            <td class="text-end">
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $skill->percentage }}%" aria-valuenow="{{ $skill->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">{{ $skill->percentage }}%</small>
                            </td>
                            <td class="text-end">
                                <span class="badge {{ $skill->active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $skill->active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-end">{{ $skill->sort_order }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-sm btn-outline-primary me-2">تعديل</a>
                                <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد مهارات مضافة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $skills->links() }}
    </div>
</div>
@endsection
