@extends('admin.layouts.admin')

@section('title', 'كلمات الصفحات')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted">إدارة كلمات الصفحات الثابتة وقوائم الأقسام</div>
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
                    <th>الصفحة</th>
                    <th>Route</th>
                    <th class="text-end">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td class="fw-bold">{{ $page->name }}</td>
                    <td class="text-muted">{{ $page->route_name ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.seo-pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">تعديل الكلمات</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection