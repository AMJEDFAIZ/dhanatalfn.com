@extends('admin.layouts.admin')
@section('title', 'الرسائل')
@section('breadcrumbs')
    <li class="breadcrumb-item active">الرسائل</li>
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary {{ $status==='all'?'active':'' }}">الكل ({{ $counts['all'] }})</a>
            <a href="{{ route('admin.messages.index', ['status'=>'unread']) }}" class="btn btn-outline-secondary {{ $status==='unread'?'active':'' }}">غير مقروء ({{ $counts['unread'] }})</a>
            <a href="{{ route('admin.messages.index', ['status'=>'read']) }}" class="btn btn-outline-secondary {{ $status==='read'?'active':'' }}">مقروء ({{ $counts['read'] }})</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>الهاتف</th>
                    <th>الموضوع</th>
                    <th>الحالة</th>
                    <th>تاريخ الإرسال</th>
                    <th class="text-end">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td dir="ltr">{{ $m->email }}</td>
                    <td dir="ltr">{{ $m->phone }}</td>
                    <td>{{ $m->subject }}</td>
                    <td>
                        @if(!$m->is_read)
                            <span class="badge bg-warning text-dark">غير مقروء</span>
                        @else
                            <span class="badge bg-success">مقروء</span>
                        @endif
                    </td>
                    <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.messages.show', $m->id) }}" class="btn btn-sm btn-primary">عرض</a>
                        <form action="{{ route('admin.messages.update', $m->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="{{ $m->is_read ? 'mark_unread' : 'mark_read' }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">{{ $m->is_read ? 'تعليم كغير مقروء' : 'تعليم كمقروء' }}</button>
                        </form>
                        <form action="{{ route('admin.messages.destroy', $m->id) }}" method="post" class="d-inline" onsubmit="return confirm('حذف الرسالة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">لا توجد رسائل</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $messages->links() }}
    </div>
@endsection
