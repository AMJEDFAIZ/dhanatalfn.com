<hr class="my-3">
<h3 class="h6 fw-bold mb-3">الكلمات المفتاحية والوسوم</h3>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card p-3">
            <div class="fw-bold mb-2">كلمات SEO (حد أقصى {{ \App\Services\KeywordService::META_LIMIT }})</div>
            <select name="meta_keyword_ids[]" class="form-select" multiple size="10">
                @foreach ($keywords as $k)
                <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('meta_keyword_ids', $metaKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
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
                <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('content_keyword_ids', $contentKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
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