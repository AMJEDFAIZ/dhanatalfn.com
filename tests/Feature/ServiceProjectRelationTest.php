<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ServiceProjectRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_can_have_projects()
    {
        $service = Service::create([
            'title' => 'Test Service ' . time(),
            'slug' => 'test-service-' . time(),
            'description' => 'Test Description',
            'active' => true
        ]);

        $project = Project::create([
            'title' => 'Test Project ' . time(),
            'slug' => 'test-project-' . time(),
            'description' => 'Test Description',
            'service_id' => $service->id,
            'active' => true
        ]);

        $project->refresh();
        $service->refresh();

        $this->assertEquals($service->id, $project->service_id);
        $this->assertTrue($service->projects->contains($project));
        $this->assertEquals($service->id, $project->service->id);
    }

    public function test_delete_service_cascades_to_projects()
    {
        $service = Service::create([
            'title' => 'Cascade Service ' . time(),
            'slug' => 'cascade-service-' . time(),
            'active' => true
        ]);

        $project = Project::create([
            'title' => 'Cascade Project ' . time(),
            'slug' => 'cascade-project-' . time(),
            'service_id' => $service->id,
            'active' => true
        ]);

        $projectId = $project->id;

        $service->delete();

        $this->assertDatabaseMissing('projects', ['id' => $projectId]);
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_admin_can_assign_service_to_project()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $service = Service::create([
            'title' => 'Admin Service',
            'slug' => 'admin-service-' . time(),
            'active' => true
        ]);

        $title = 'Admin Project ' . time();

        $response = $this->actingAs($user)->post(route('admin.projects.store'), [
            'title' => $title,
            'service_id' => $service->id,
            'main_image' => UploadedFile::fake()->image('project.webp'),
            'active' => 1
        ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', [
            'title' => $title,
            'service_id' => $service->id
        ]);
    }

    public function test_performance_with_many_projects()
    {
        $service = Service::create([
            'title' => 'Performance Service',
            'slug' => 'perf-service',
            'active' => true
        ]);

        // Create 1000 projects linked to this service
        $projectsData = [];
        for ($i = 0; $i < 1000; $i++) {
            $projectsData[] = [
                'title' => 'Project ' . $i,
                'slug' => 'project-' . $i . '-' . Str::random(5),
                'service_id' => $service->id,
                'main_image' => 'path/to/img.jpg',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];

            if (count($projectsData) >= 100) {
                 Project::insert($projectsData);
                 $projectsData = [];
            }
        }
        if (!empty($projectsData)) {
            Project::insert($projectsData);
        }

        // Measure query time
        $start = microtime(true);
        // Using lazy loading as requested by user or simple relation access
        // User asked for "getProjectsByService" but in controller it might be different.
        // We test the relation retrieval speed.
        $projects = $service->projects()->paginate(10);
        $end = microtime(true);

        $executionTime = $end - $start;

        $this->assertCount(10, $projects);
        $this->assertLessThan(1.0, $executionTime, "Query took too long: {$executionTime}s");
    }
}
