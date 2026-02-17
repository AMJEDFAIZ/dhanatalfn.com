<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['site_name'] ?? 'لوحة الإدارة') - إدارة</title>
    @if (isset($settings['site_favicon']))
    <link rel="icon" href="{{ asset('storage/' . $settings['site_favicon']) ?? asset('favicon.ico') }}"
        type="image/x-icon">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #0f3c5c;
            --color-secondary: #ffd54d;
            --color-bg: #f8f9fa;
            --color-text: #111827;
            --radius: 10px;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            --transition: 200ms ease;
        }

        body {
            background: var(--color-bg);
            color: var(--color-text);
            font-family: 'Cairo', system-ui, -apple-system, 'Segoe UI', sans-serif
        }

        .navbar {
            background: var(--color-primary)
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: #fff
        }

        .navbar .nav-link:hover {
            color: var(--color-secondary);
            transition: var(--transition)
        }

        .sidebar {
            width: 280px;
            background: #fff;
            border-inline-start: 1px solid #e5e7eb;
            box-shadow: var(--shadow);
            position: fixed;
            z-index: 1520;
        }

        .sidebar .list-group-item {
            border: 0;
            border-radius: 8px;
            margin: 4px 0
        }

        .sidebar .list-group-item.active {
            background: var(--color-primary);
            color: #fff
        }

        .content-wrapper {
            margin-inline-start: 280px;
            transition: var(--transition)
        }

        .content-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .btn-primary {
            background: var(--color-primary);
            border-color: var(--color-primary)
        }

        .btn-primary:hover {
            filter: brightness(1.1);
            transition: var(--transition)
        }

        .btn-secondary {
            background: var(--color-secondary);
            border-color: var(--color-secondary);
            color: #0f3c5c
        }

        .btn-secondary:hover {
            filter: brightness(1.05);
            transition: var(--transition)
        }

        .footer {
            background: #fff;
            border-top: 1px solid #e5e7eb;
            bottom: 0;
        }

        .breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            content: "/";
            padding-inline: 0.5rem
        }

        @media (max-width:992px) {
            .content-wrapper {
                margin-inline-start: 0
            }
        }
    </style>
    @stack('styles')
    <script src="cdn.tiny.cloud"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-2 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                @php $logo = isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('logo.PNG'); @endphp
                <img src="{{ $logo }}" alt="logo" class="rounded me-2" style="height:32px">
                <span>{{ $settings['site_name'] ?? 'لوحة الإدارة' }}</span>
            </a>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle position-relative" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if (($unreadMessagesCount ?? 0) > 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">الإشعارات</h6>
                        </li>
                        @if (($unreadMessagesCount ?? 0) > 0)
                        <li><a class="dropdown-item d-flex justify-content-between align-items-center"
                                href="{{ route('admin.messages.index', ['status' => 'unread']) }}">رسائل غير
                                مقروءة<span class="badge bg-danger ms-2">{{ $unreadMessagesCount }}</span></a></li>
                        @else
                        <li><a class="dropdown-item" href="#">لا توجد إشعارات جديدة</a></li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>{{ Auth::user()->name ?? 'المستخدم' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">الملف الشخصي</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">تسجيل الخروج</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Sidebar for small screens (offcanvas) -->
    <div class="offcanvas offcanvas-start sidebar d-lg-none" tabindex="-1" id="adminSidebar"
        aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarLabel">{{ $settings['site_name'] ?? 'لوحة الإدارة' }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="list-group">
                <a href="{{ route('admin.dashboard') }}"
                    class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span><i class="bi bi-speedometer2 ms-2"></i> لوحة التحكم</span>
                </a>
                <button class="list-group-item d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#contentMenu" aria-expanded="true">
                    <span><i class="bi bi-grid ms-2"></i> المحتوى</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="collapse show" id="contentMenu">
                    <a href="{{ route('admin.services.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i
                            class="bi bi-tools ms-2"></i> الخدمات</a>
                    <a href="{{ route('admin.projects.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><i
                            class="bi bi-building ms-2"></i> المشاريع</a>
                    <a href="{{ route('admin.gallery.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}"><i
                            class="bi bi-images ms-2"></i> معرض الصور</a>
                    <a href="{{ route('admin.blog.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"><i
                            class="bi bi-journal-text ms-2"></i> المدونة</a>
                    <a href="{{ route('admin.keywords.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.keywords.*') ? 'active' : '' }}"><i
                            class="bi bi-tags ms-2"></i> الكلمات المفتاحية</a>
                    <a href="{{ route('admin.seo-pages.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.seo-pages.*') ? 'active' : '' }}"><i
                            class="bi bi-globe2 ms-2"></i> كلمات الصفحات</a>
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"><i
                            class="bi bi-chat-quote ms-2"></i> آراء العملاء</a>
                    <a href="{{ route('admin.skills.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}"><i
                            class="bi bi-graph-up-arrow ms-2"></i> المهارات</a>
                    <a href="{{ route('admin.messages.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"><i
                            class="bi bi-envelope ms-2"></i> الرسائل</a>
                </div>
                <a href="{{ route('admin.settings.index') }}"
                    class="list-group-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i
                        class="bi bi-gear ms-2"></i> الإعدادات</a>
            </div>
        </div>
    </div>
    <!-- Sidebar for large screens (static) -->
    <div class="sidebar d-none d-lg-block">
        <div class="p-3">
            <div class="list-group">
                <a href="{{ route('admin.dashboard') }}"
                    class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span><i class="bi bi-speedometer2 ms-2"></i> لوحة التحكم</span>
                </a>
                <button class="list-group-item d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#contentMenuLg" aria-expanded="true">
                    <span><i class="bi bi-grid ms-2"></i> المحتوى</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="collapse show" id="contentMenuLg">
                    <a href="{{ route('admin.services.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i
                            class="bi bi-tools ms-2"></i> الخدمات</a>
                    <a href="{{ route('admin.projects.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><i
                            class="bi bi-building ms-2"></i> المشاريع</a>
                    <a href="{{ route('admin.gallery.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}"><i
                            class="bi bi-images ms-2"></i> معرض الصور</a>
                    <a href="{{ route('admin.blog.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"><i
                            class="bi bi-journal-text ms-2"></i> المدونة</a>
                    <a href="{{ route('admin.keywords.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.keywords.*') ? 'active' : '' }}"><i
                            class="bi bi-tags ms-2"></i> الكلمات المفتاحية</a>
                    <a href="{{ route('admin.seo-pages.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.seo-pages.*') ? 'active' : '' }}"><i
                            class="bi bi-globe2 ms-2"></i> كلمات الصفحات</a>
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"><i
                            class="bi bi-chat-quote ms-2"></i> آراء العملاء</a>
                    <a href="{{ route('admin.skills.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}"><i
                            class="bi bi-graph-up-arrow ms-2"></i> المهارات</a>
                    <a href="{{ route('admin.messages.index') }}"
                        class="list-group-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"><i
                            class="bi bi-envelope ms-2"></i> الرسائل</a>
                </div>
                <a href="{{ route('admin.settings.index') }}"
                    class="list-group-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i
                        class="bi bi-gear ms-2"></i> الإعدادات</a>
            </div>
        </div>
    </div>
    <main class="content-wrapper">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 fw-bold mb-0">@yield('title', 'لوحة الإدارة')</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            </div>
            <div class="content-card p-3">
                @yield('content')
            </div>
        </div>
    </main>
    <footer class="footer py-3 position-fixed bottom-0 w-100">
        <div class="container-fluid d-flex justify-content-between">
            <div>© {{ date('Y') }} {{ $settings['site_name'] ?? 'النظام' }}</div>
            <div>الإصدار: {{ app()->version() }}</div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>