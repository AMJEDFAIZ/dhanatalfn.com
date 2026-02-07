<div class="w-64 bg-primary text-white min-h-screen shrink-0 hidden md:block">
    <div class="p-4 flex items-center justify-center border-b border-white/10">
        <h2 class="text-xl font-bold">{{ $settings['site_name'] ?? ' الفـن الـحـديـث' }}</h2>
    </div>

    <nav class="mt-4 px-2 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
            <span class="ml-3">📊</span>
            <span>لوحة التحكم</span>
        </a>

        <a href="{{ route('admin.services.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.services.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">🛠️</span>
            <span>الخدمات</span>
        </a>

        <a href="{{ route('admin.projects.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.projects.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">🏗️</span>
            <span>المشاريع</span>
        </a>

        <a href="{{ route('admin.gallery.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.gallery.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">🖼️</span>
            <span>معرض الصور</span>
        </a>

        <a href="{{ route('admin.blog.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.blog.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">📝</span>
            <span>المدونة</span>
        </a>

        <a href="{{ route('admin.testimonials.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.testimonials.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">💬</span>
            <span>آراء العملاء</span>
        </a>

        <a href="{{ route('admin.skills.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.skills.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">💪</span>
            <span>المهارات</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
            class="flex items-center px-4 py-3 rounded hover:bg-white/10 {{ request()->routeIs('admin.settings.*') ? 'bg-white/10' : '' }}">
            <span class="ml-3">⚙️</span>
            <span>الإعدادات</span>
        </a>
    </nav>
</div>
