@extends('admin.layouts.admin')

@section('title', 'تعديل كلمات صفحة')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="h5 fw-bold mb-0">{{ $seoPage->name }}</div>
            <div class="text-muted small">{{ $seoPage->route_name ?? $seoPage->key }}</div>
        </div>
        <a href="{{ route('admin.seo-pages.index') }}" class="btn btn-outline-secondary">عودة</a>
    </div>

    <form method="POST" action="{{ route('admin.seo-pages.update', $seoPage) }}">
        @csrf
        @method('PUT')

        @include('admin.partials.keywords-fields', [
        'keywords' => $keywords,
        'metaKeywordIds' => $metaKeywordIds,
        'contentKeywordIds' => $contentKeywordIds,
        'keywordPrimaryIds' => $keywordPrimaryIds,
        'keywordWeights' => $keywordWeights,
        ])

        <div class="text-end mt-3">
            <button class="btn btn-primary" type="submit">حفظ</button>
        </div>
    </form>
</div>
@endsection