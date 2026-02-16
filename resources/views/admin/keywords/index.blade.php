@extends('admin.layouts.admin')

@section('title', 'الكلمات المفتاحية')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.keywords.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ $search ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">بحث</button>
            @if (!empty($search ?? null))
            <a href="{{ route('admin.keywords.index') }}" class="btn btn-outline-dark">مسح</a>
            @endif
        </form>
        <a href="{{ route('admin.keywords.create') }}" class="btn btn-primary">
            إضافة كلمة
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>الكلمة</th>
                    <th>اللغة</th>
                    <th>الحالة</th>
                    <th>الاستخدام</th>
                    <th class="text-end">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($keywords as $keyword)
                @php
                $usage = (int) $keyword->services_count + (int) $keyword->projects_count + (int) $keyword->blog_posts_count + (int) $keyword->seo_pages_count;
                @endphp
                <tr>
                    <td class="fw-bold">{{ $keyword->name }}</td>
                    <td>{{ $keyword->locale }}</td>
                    <td>
                        @if ($keyword->active)
                        <span class="badge bg-success">نشط</span>
                        @else
                        <span class="badge bg-secondary">معطل</span>
                        @endif
                    </td>
                    <td>{{ $usage }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.keywords.show', $keyword) }}" class="btn btn-sm btn-outline-dark">عرض</a>
                        <a href="{{ route('admin.keywords.edit', $keyword) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                        <form action="{{ route('admin.keywords.destroy', $keyword) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit" {{ $usage > 0 ? 'disabled' : '' }}>حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">لا توجد كلمات.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $keywords->links() }}
    </div>
</div>
@endsection