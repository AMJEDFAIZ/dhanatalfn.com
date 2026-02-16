<?php

namespace App\Services;

use App\Models\SeoPage;

class SeoPageService
{
    public function ensureDefaults(): void
    {
        foreach ($this->defaults() as $row) {
            SeoPage::firstOrCreate(
                ['key' => $row['key']],
                [
                    'route_name' => $row['route_name'],
                    'name' => $row['name'],
                    'active' => true,
                ]
            );
        }
    }

    public function defaults(): array
    {
        return [
            ['key' => 'home', 'route_name' => 'home', 'name' => 'الصفحة الرئيسية'],
            ['key' => 'about', 'route_name' => 'about', 'name' => 'من نحن'],
            ['key' => 'contact', 'route_name' => 'contact', 'name' => 'تواصل معنا'],
            ['key' => 'services_index', 'route_name' => 'services.index', 'name' => 'الخدمات'],
            ['key' => 'projects_index', 'route_name' => 'projects.index', 'name' => 'المشاريع'],
            ['key' => 'blog_index', 'route_name' => 'blog.index', 'name' => 'المدونة'],
        ];
    }

    public function getByKey(string $key): ?SeoPage
    {
        $this->ensureDefaults();

        return SeoPage::where('key', $key)->first();
    }
}
