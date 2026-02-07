@extends('admin.layouts.admin')

@section('title', 'لوحة التحكم')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">لوحة التحكم</li>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div id="login-alert" class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                <strong>تم تسجيل الدخول بنجاح</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <div class="col-12">
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">إحصائيات المحتوى</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between"><span>الخدمات</span><span>{{ $counts['services'] }} ({{ $percentages['services'] }}%)</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span>المشاريع</span><span>{{ $counts['projects'] }} ({{ $percentages['projects'] }}%)</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span>المعرض</span><span>{{ $counts['gallery'] }} ({{ $percentages['gallery'] }}%)</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span>المدونة</span><span>{{ $counts['blog'] }} ({{ $percentages['blog'] }}%)</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span>آراء العملاء</span><span>{{ $counts['testimonials'] }} ({{ $percentages['testimonials'] }}%)</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span>المهارات</span><span>{{ $counts['skills'] }} ({{ $percentages['skills'] }}%)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">نسب الأقسام</h5>
                            <canvas id="chart-pie" height="220"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">مقارنة الأقسام</h5>
                            <canvas id="chart-bar" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>لوحة الرسائل</span>
                            <div class="d-flex gap-2">
                                <span class="badge bg-danger">غير مقروء: {{ $messageCounts['unread'] }}</span>
                                <form action="{{ route('admin.messages.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">تمييز الكل كمقروء</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label">تصفية حسب النوع</label>
                                <select id="message-filter" class="form-select form-select-sm">
                                    <option value="all">الكل</option>
                                    <option value="استشارة">استشارة ({{ $messageCounts['by_subject']['استشارة'] }})</option>
                                    <option value="عرض سعر">عرض سعر ({{ $messageCounts['by_subject']['عرض سعر'] }})</option>
                                    <option value="دعم">دعم ({{ $messageCounts['by_subject']['دعم'] }})</option>
                                    <option value="أخرى">أخرى ({{ $messageCounts['by_subject']['أخرى'] }})</option>
                                </select>
                            </div>
                            <ul id="messages-list" class="list-group">
                                @foreach($unreadMessages as $m)
                                    <li class="list-group-item d-flex justify-content-between align-items-center" data-subject="{{ $m->subject }}">
                                        <div>
                                            <div class="fw-bold">{{ $m->subject }}</div>
                                            <div class="text-muted small">{{ $m->email }} • {{ $m->created_at->format('Y-m-d H:i') }}</div>
                                        </div>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.messages.show', $m->id) }}">فتح</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">آخر 3 عناصر مضافة</div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                <div class="col">
                                    <h6 class="fw-bold">الخدمات</h6>
                                    <ul class="list-group">
                                        @foreach($latest['services'] as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>{{ $item->title }}</span>
                                                <span class="text-muted small">{{ $item->created_at->format('Y-m-d') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold">المشاريع</h6>
                                    <ul class="list-group">
                                        @foreach($latest['projects'] as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>{{ $item->title }}</span>
                                                <span class="text-muted small">{{ $item->created_at->format('Y-m-d') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold">المدونة</h6>
                                    <ul class="list-group">
                                        @foreach($latest['blog'] as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>{{ $item->title }}</span>
                                                <span class="text-muted small">{{ $item->created_at->format('Y-m-d') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold">المعرض</h6>
                                    <ul class="list-group">
                                        @foreach($latest['gallery'] as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>{{ $item->title ?? ('صورة #' . $item->id) }}</span>
                                                <span class="text-muted small">{{ $item->created_at->format('Y-m-d') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">معاينة الأقسام</div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                        <div class="col">
                            <h6 class="fw-bold mb-2">الخدمات</h6>
                            @foreach($previews['services'] as $item)
                                <div class="section-preview-item d-flex align-items-center mb-2">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/'.$item->image_path) }}" class="section-preview-thumb rounded me-2" alt="{{ $item->title }}">
                                    @endif
                                    <div class="flex-grow-1">
                                        <a href="{{ route('services.show', $item->slug) }}" target="_blank" class="section-preview-title">{{ $item->title }}</a>
                                        <div class="section-preview-meta">{{ Str::limit(strip_tags($item->description), 60) }}</div>
                                        <div class="section-preview-meta">{{ $item->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col">
                            <h6 class="fw-bold mb-2">المشاريع</h6>
                            @foreach($previews['projects'] as $item)
                                <div class="section-preview-item d-flex align-items-center mb-2">
                                    @if($item->main_image)
                                        <img src="{{ asset('storage/'.$item->main_image) }}" class="section-preview-thumb rounded me-2" alt="{{ $item->title }}">
                                    @endif
                                    <div class="flex-grow-1">
                                        <a href="{{ route('projects.show', $item->slug) }}" target="_blank" class="section-preview-title">{{ $item->title }}</a>
                                        <div class="section-preview-meta">{{ Str::limit(strip_tags($item->description), 60) }}</div>
                                        <div class="section-preview-meta">{{ $item->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col">
                            <h6 class="fw-bold mb-2">المدونة</h6>
                            @foreach($previews['blog'] as $item)
                                <div class="section-preview-item d-flex align-items-center mb-2">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/'.$item->image_path) }}" class="section-preview-thumb rounded me-2" alt="{{ $item->title }}">
                                    @endif
                                    <div class="flex-grow-1">
                                        <a href="{{ route('blog.show', $item->slug) }}" target="_blank" class="section-preview-title">{{ $item->title }}</a>
                                        <div class="section-preview-meta">{{ Str::limit(strip_tags($item->content), 20) }}</div>
                                        <div class="section-preview-meta">{{ $item->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col">
                            <h6 class="fw-bold mb-2">المعرض</h6>
                            @foreach($previews['gallery'] as $item)
                                <div class="section-preview-item d-flex align-items-center mb-2">
                                    <img src="{{ asset($item->image_path ? 'storage/'.$item->image_path : 'placeholder.png') }}" class="section-preview-thumb rounded me-2" alt="{{ 'صورة #'.$item->id }}">
                                    <div class="flex-grow-1">
                                        <span class="section-preview-title d-block">صورة #{{ $item->id }}</span>
                                        <div class="section-preview-meta">{{ $item->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .section-preview-item{padding:.75rem;border:1px solid var(--bs-border-color);border-radius:var(--bs-border-radius);background-color:var(--bs-body-bg);transition:box-shadow .2s ease,transform .2s ease;animation:fadeUp .2s ease both}
        .section-preview-item:hover{box-shadow:0 .25rem .75rem rgba(0,0,0,.075);transform:translateY(-2px)}
        .section-preview-thumb{width:48px;height:48px;object-fit:cover;border-radius:.375rem}
        .section-preview-title{font-weight:600;color:var(--bs-primary);text-decoration:none;font-size:.95rem}
        .section-preview-title:hover{text-decoration:underline}
        .section-preview-title:focus-visible{outline:2px solid var(--bs-primary);outline-offset:2px;border-radius:.25rem}
        .section-preview-meta{font-size:.875rem;color:var(--bs-secondary-color)}
        @keyframes fadeUp{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
        @media (prefers-reduced-motion: reduce){
            .section-preview-item{animation:none;transition:none}
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/js/admin/dashboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        window.__DASHBOARD_DATA__ = {
            counts: @json($counts),
            percentages: @json($percentages)
        };
    </script>
@endpush
