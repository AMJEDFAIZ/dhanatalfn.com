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

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card p-3">
                    <div class="fw-bold mb-2">كلمات SEO (حد أقصى {{ \App\Services\KeywordService::META_LIMIT }})</div>

                    <select name="meta_keyword_ids[]" class="form-select" multiple size="10">
                        @foreach ($keywords as $k)
                        <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('meta_keyword_ids', $metaKeywordIds) ?? []) ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('meta_keyword_ids')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-3">
                        <label class="form-label" for="meta_keyword_names">إضافة كلمات جديدة (مفصولة بفواصل)</label>
                        <input class="form-control" id="meta_keyword_names" name="meta_keyword_names" type="text" value="{{ old('meta_keyword_names') }}" placeholder="مثال: دهانات داخلية, بديل الرخام">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card p-3">
                    <div class="fw-bold mb-2">وسوم المحتوى (حد أقصى {{ \App\Services\KeywordService::CONTENT_LIMIT }})</div>

                    <select name="content_keyword_ids[]" class="form-select" multiple size="10">
                        @foreach ($keywords as $k)
                        <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('content_keyword_ids', $contentKeywordIds) ?? []) ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('content_keyword_ids')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-3">
                        <label class="form-label" for="content_keyword_names">إضافة كلمات جديدة (مفصولة بفواصل)</label>
                        <input class="form-control" id="content_keyword_names" name="content_keyword_names" type="text" value="{{ old('content_keyword_names') }}" placeholder="مثال: تشطيبات, ورق جدران">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-primary" type="submit">حفظ</button>
        </div>
    </form>
</div>
@endsection