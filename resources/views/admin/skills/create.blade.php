@extends('admin.layouts.admin')

@section('title', 'إضافة مهارة جديدة')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h6 fw-bold mb-0">إضافة مهارة جديدة</h1>
        <a href="{{ route('admin.skills.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.skills.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم المهارة</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="percentage" class="form-label">النسبة المئوية (%)</label>
                    <input type="number" name="percentage" id="percentage" value="{{ old('percentage') }}" min="0" max="100" class="form-control" required>
                    @error('percentage') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="sort_order" class="form-label">الترتيب</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', 1) ? 'checked' : '' }} class="form-check-input">
                        <label for="active" class="form-check-label">نشط (يظهر في الموقع)</label>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
