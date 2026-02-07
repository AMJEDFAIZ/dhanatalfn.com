@extends('admin.layouts.admin')
@section('title', 'عرض رسالة')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">الرسائل</a></li>
    <li class="breadcrumb-item active">عرض</li>
@endsection
@section('content')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card p-3">
                <div class="mb-2"><strong>الاسم:</strong> {{ $message->name }}</div>
                <div class="mb-2"><strong>البريد:</strong> <span dir="ltr">{{ $message->email }}</span></div>
                <div class="mb-2"><strong>الهاتف:</strong> <span dir="ltr">{{ $message->phone }}</span></div>
                <div class="mb-2"><strong>الموضوع:</strong> {{ $message->subject }}</div>
                <div class="mb-2"><strong>التاريخ:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</div>
                <div class="mb-3"><strong>الرسالة:</strong><div class="mt-2">{{ $message->message }}</div></div>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.messages.update', $message->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="{{ $message->is_read ? 'mark_unread' : 'mark_read' }}">
                        <button class="btn btn-outline-secondary">{{ $message->is_read ? 'تعليم كغير مقروء' : 'تعليم كمقروء' }}</button>
                    </form>
                    <form action="{{ route('admin.messages.destroy', $message->id) }}" method="post" onsubmit="return confirm('حذف الرسالة؟')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-3">
                <h5 class="mb-3">الرد على الرسالة</h5>
                @if($message->replied_at)
                    <div class="mb-2 text-muted">تم الرد في: {{ $message->replied_at->format('Y-m-d H:i') }}</div>
                    <div class="border p-2 mb-3">{{ $message->reply_content }}</div>
                @endif
                <form action="{{ route('admin.messages.update', $message->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reply">
                    <div class="mb-3">
                        <textarea name="reply_content" rows="6" class="form-control" placeholder="نص الرد"></textarea>
                    </div>
                    <button class="btn btn-primary">إرسال الرد</button>
                </form>
            </div>
        </div>
    </div>
@endsection
