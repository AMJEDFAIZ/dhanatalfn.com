<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_slug_on_create_for_service(): void
    {
        $s1 = Service::create(['title' => 'دهانات داخلية', 'active' => true]);
        $s2 = Service::create(['title' => 'دهانات داخلية', 'active' => true]);

        $this->assertNotEquals($s1->slug, $s2->slug);
        $this->assertTrue(str_starts_with($s2->slug, $s1->slug) || str_starts_with($s2->slug, 'dehanat-dakhilia'));
    }

    public function test_generates_unique_slug_on_update_for_project(): void
    {
        $p1 = Project::create(['title' => 'مشروع اختبار', 'active' => true]);
        $p2 = Project::create(['title' => 'مشروع اختبار', 'active' => true]);

        $p1->title = 'مشروع اختبار';
        $p1->slug = null;
        $p1->save();

        $this->assertNotEquals($p1->slug, $p2->slug);
    }
}
