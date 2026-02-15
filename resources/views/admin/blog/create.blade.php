@extends('admin.layouts.admin')

@section('title', 'إضافة مقال جديد')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 fw-bold mb-0">إضافة مقال جديد</h1>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="title">عنوان المقال</label>
                <input class="form-control @error('title') is-invalid @enderror" id="title" type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="content">المحتوى</label>
                <textarea class="rich-text form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                @error('content')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="slug">السلاج (عنوان الرابط)</label>
                <input class="form-control @error('slug') is-invalid @enderror" id="slug" type="text" name="slug" value="{{ old('slug') }}" placeholder="مثال: blog-post-title">
                @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">اتركه فارغاً ليتم توليده تلقائياً من العنوان.</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="image">صورة المقال (اختياري)</label>
                <input class="form-control" id="image" type="file" name="image">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label" for="published_at">تاريخ النشر</label>
                    <input class="form-control" id="published_at" type="date" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}">
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">نشط</label>
                    </div>
                </div>
            </div>

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

            <hr class="my-3">
            <h3 class="h6 fw-bold mb-3">الأسئلة الشائعة (FAQ Schema)</h3>
            <div id="faqs-container">
                @if(old('faqs'))
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

            <div class="text-end">
                <button class="btn btn-primary" type="submit">حفظ المقال</button>
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
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput) {
            titleInput.addEventListener('blur', function() {
                if (!slugInput.value) {
                    slugInput.value = titleInput.value.trim().toLowerCase().replace(/\s+/g, '-');
                }
            });
        }

        let faqIndex = {{is_array(old('faqs')) ? count(old('faqs')) : 0}};

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