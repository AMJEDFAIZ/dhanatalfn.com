<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminSettingsUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_site_logo_and_favicon(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.settings.update'), [
            'site_logo' => UploadedFile::fake()->image('logo.png', 400, 200),
            'site_favicon' => UploadedFile::fake()->create('favicon.ico', 20, 'image/x-icon'),
        ]);

        $response->assertStatus(302);

        $logoPath = Setting::where('key', 'site_logo')->value('value');
        $faviconPath = Setting::where('key', 'site_favicon')->value('value');

        $this->assertIsString($logoPath);
        $this->assertIsString($faviconPath);
        $this->assertNotSame('', $logoPath);
        $this->assertNotSame('', $faviconPath);

        $this->assertSame('settings/favicon.ico', $faviconPath);
        $this->assertTrue(file_exists(storage_path('app/public/' . $logoPath)));
        $this->assertTrue(file_exists(storage_path('app/public/' . $faviconPath)));
    }
}
