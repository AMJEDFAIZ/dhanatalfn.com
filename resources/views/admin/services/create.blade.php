@extends('admin.layouts.admin')

@section('title', 'إضافة خدمة جديدة')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إضافة خدمة جديدة</h1>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
            عودة للقائمة
        </a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="title">عنوان الخدمة</label>
                <input class="form-control @error('title') is-invalid @enderror" id="title" type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">وصف الخدمة</label>
                <textarea class="rich-text form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="icon">الأيقونة (اختياري - FontAwesome class)</label>
                <input class="form-control" id="icon" type="text" name="icon" value="{{ old('icon') }}" placeholder="fa-solid fa-tools">
            </div>

            <div class="mb-3">
                <label class="form-label" for="slug">السلاج (عنوان الرابط)</label>
                <input class="form-control @error('slug') is-invalid @enderror" id="slug" type="text" name="slug" value="{{ old('slug') }}" placeholder="مثال: service-title">
                @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">اتركه فارغاً ليتم توليده تلقائياً من العنوان.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="image">صورة الخدمة (اختياري)</label>
                <input class="form-control" id="image" type="file" name="image">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label" for="sort_order">الترتيب</label>
                    <input class="form-control" id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">نشط</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">المشاريع المرتبطة (اختياري)</label>
                <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                    @if($projects->count() > 0)
                    @foreach($projects as $project)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="project_ids[]" value="{{ $project->id }}" id="project_{{ $project->id }}" {{ in_array($project->id, old('project_ids', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="project_{{ $project->id }}">
                            {{ $project->title }}
                        </label>
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted small mb-0">لا توجد مشاريع غير مرتبطة حالياً.</p>
                    @endif
                </div>
                <small class="text-muted">يمكنك اختيار مشاريع لربطها بهذه الخدمة مباشرة.</small>
            </div>

            <hr class="my-3">
            <h3 class="h6 fw-bold mb-3">الأسئلة الشائعة (FAQ - Schema)</h3>
            <div id="faqs-container">
                @if(is_array(old('faqs')))
                @foreach(old('faqs') as $index => $faq)
                <div class="card mb-3 faq-item">
                    <div class="card-body bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">سؤال {{ $index + 1 }}</span>
                            <button type="button" class="btn btn-sm btn-danger remove-faq">حذف</button>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="faqs[{{ $index }}][question]" class="form-control" placeholder="السؤال" value="{{ $faq['question'] ?? '' }}">
                        </div>
                        <div>
                            <textarea name="faqs[{{ $index }}][answer]" class="form-control" placeholder="الإجابة" rows="2">{{ $faq['answer'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-success mb-3" id="add-faq">
                <i class="fas fa-plus"></i> إضافة سؤال جديد
            </button>

            <hr class="my-3">
            <h3 class="h6 fw-bold mb-3">تحسين محركات البحث (SEO)</h3>

            <div class="mb-3">
                <label class="form-label" for="meta_title">عنوان الميتا (Meta Title)</label>
                <input class="form-control" id="meta_title" type="text" name="meta_title" value="{{ old('meta_title') }}">
                <small class="text-muted d-block mt-1">يترك فارغاً لاستخدام العنوان الافتراضي.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="meta_description">وصف الميتا (Meta Description)</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                <small class="text-muted d-block mt-1">وصف مختصر يظهر في نتائج البحث.</small>
            </div>

            @include('admin.partials.keywords-fields', [
            'keywords' => $keywords,
            'metaKeywordIds' => [],
            'contentKeywordIds' => [],
            'keywordPrimaryIds' => [],
            'keywordWeights' => [],
            ])

            <div class="text-end">
                <button class="btn btn-primary" type="submit">حفظ الخدمة</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let faqIndex = {{ is_array(old('faqs')) ? count(old('faqs')) : 0 }};
        document.getElementById('add-faq').addEventListener('click', function() {
            const container = document.getElementById('faqs-container');
            const template = `
                <div class="card mb-3 faq-item">
                    <div class="card-body bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">سؤال جديد</span>
                            <button type="button" class="btn btn-sm btn-danger remove-faq">حذف</button>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="faqs[${faqIndex}][question]" class="form-control" placeholder="السؤال">
                        </div>
                        <div>
                            <textarea name="faqs[${faqIndex}][answer]" class="form-control" placeholder="الإجابة" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            faqIndex++;
        });
        document.getElementById('faqs-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-faq')) {
                e.target.closest('.faq-item').remove();
            }
        });
    });
</script>
@endpush