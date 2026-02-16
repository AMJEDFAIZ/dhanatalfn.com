@extends('admin.layouts.admin')

@section('title', 'تفاصيل كلمة مفتاحية')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="h5 fw-bold mb-1">{{ $keyword->name }}</div>
            <div class="text-muted small">Slug: {{ $keyword->slug }} | Locale: {{ $keyword->locale }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.keywords.edit', $keyword) }}" class="btn btn-outline-primary">تعديل</a>
            <a href="{{ route('admin.keywords.index') }}" class="btn btn-outline-secondary">عودة</a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">الحالة</div>
                <div class="fw-bold">{{ $keyword->active ? 'نشط' : 'معطل' }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">الخدمات</div>
                <div class="fw-bold">{{ (int) ($keyword->services_count ?? 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">المشاريع</div>
                <div class="fw-bold">{{ (int) ($keyword->projects_count ?? 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">المدونة</div>
                <div class="fw-bold">{{ (int) ($keyword->blog_posts_count ?? 0) }}</div>
            </div>
        </div>
    </div>

    @if (!empty($keyword->description))
    <div class="card p-3 mb-3">
        <div class="fw-bold mb-2">وصف</div>
        <div class="text-muted">{{ $keyword->description }}</div>
    </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card p-3">
                <div class="fw-bold mb-2">الخدمات المرتبطة</div>
                @if ($services->count() > 0)
                <ul class="mb-0">
                    @foreach ($services as $item)
                    <li><a href="{{ route('admin.services.edit', $item) }}">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
                @else
                <div class="text-muted">لا يوجد</div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3">
                <div class="fw-bold mb-2">المشاريع المرتبطة</div>
                @if ($projects->count() > 0)
                <ul class="mb-0">
                    @foreach ($projects as $item)
                    <li><a href="{{ route('admin.projects.edit', $item) }}">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
                @else
                <div class="text-muted">لا يوجد</div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3">
                <div class="fw-bold mb-2">مقالات مرتبطة</div>
                @if ($blogPosts->count() > 0)
                <ul class="mb-0">
                    @foreach ($blogPosts as $item)
                    <li><a href="{{ route('admin.blog.edit', $item) }}">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
                @else
                <div class="text-muted">لا يوجد</div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3">
                <div class="fw-bold mb-2">صفحات مرتبطة</div>
                @if ($seoPages->count() > 0)
                <ul class="mb-0">
                    @foreach ($seoPages as $item)
                    <li><a href="{{ route('admin.seo-pages.edit', $item) }}">{{ $item->name }}</a></li>
                    @endforeach
                </ul>
                @else
                <div class="text-muted">لا يوجد</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection