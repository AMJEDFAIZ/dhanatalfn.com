@extends('admin.layouts.admin')

@section('title', 'آراء العملاء')
@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">+ إضافة رأي جديد</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-end">#</th>
                        <th class="text_end">الصورة</th>
                        <th class="text-end">العميل</th>
                        <th class="text-end">التقييم</th>
                        <th class="text-end">الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td class="text-end">{{ $loop->iteration }}</td>
                            <td class="text-end">
                                @if ($testimonial->image_path)
                                    <img src="{{ asset('storage/' . $testimonial->image_path) }}"
                                        alt="{{ $testimonial->client_name }}" class="rounded-circle object-cover"
                                        style="height:40px;width:40px">
                                @else
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                        style="height:40px;width:40px"><span class="text-muted small">لا توجد</span></div>
                                @endif
                            </td>
                            <td>
                                {{ $testimonial->client_name }}
                                <div class="small text-muted">{{ $testimonial->position }}</div>
                            </td>
                            <td class="text-warning">
                                @if ($testimonial->rating)
                                    {{ str_repeat('★', $testimonial->rating) }}
                                @else
                                    <span class="text-gray-300">{{ str_repeat('★', 5 - $testimonial->rating) }}</span>
                                @endif

                            </td>
                            <td class="text-end">
                                @if ($testimonial->active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                    class="btn btn-sm btn-outline-primary me-2">تعديل</a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد آراء مضافة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $testimonials->links() }}
        </div>

    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
