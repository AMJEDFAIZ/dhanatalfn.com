@extends('admin.layouts.admin')

@section('title', 'إضافة كلمة مفتاحية')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.keywords.index') }}" class="btn btn-outline-secondary">عودة</a>
    </div>

    <form action="{{ route('admin.keywords.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="name">الكلمة</label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" for="locale">اللغة</label>
                <input class="form-control @error('locale') is-invalid @enderror" id="locale" name="locale" type="text" value="{{ old('locale', 'ar') }}" placeholder="ar">
                @error('locale')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" id="active" name="active" type="checkbox" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">نشط</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">وصف الكلمة (اختياري)</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end">
            <button class="btn btn-primary" type="submit">حفظ</button>
        </div>
    </form>
</div>
@endsection