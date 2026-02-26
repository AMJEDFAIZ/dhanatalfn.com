<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Keyword;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaJsonLdTest extends TestCase
{
    use RefreshDatabase;

    private function hasNull(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $v) {
                if ($this->hasNull($v)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function extractSchema(string $html): array
    {
        $matches = [];
        preg_match('/<script[^>]*type="application\/ld\+json"[^>]*>\s*([\s\S]*?)\s*<\/script>/i', $html, $matches);
        $raw = $matches[1] ?? null;
        $this->assertNotEmpty($raw);

        $decoded = null;
        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            $this->fail('Invalid JSON-LD: ' . $e->getMessage() . "\n" . mb_substr(trim((string) $raw), 0, 500));
        }
        $this->assertIsArray($decoded, mb_substr(trim((string) $raw), 0, 500));
        $this->assertSame('https://schema.org', $decoded['@context'] ?? null);
        $this->assertIsArray($decoded['@graph'] ?? null);
        $this->assertFalse($this->hasNull($decoded['@graph']), 'Schema @graph contains null values');

        return $decoded;
    }

    private function seedSettings(): void
    {
        Setting::query()->insert([
            ['key' => 'site_name', 'value' => 'الفن الحديث', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'وصف تجريبي للموقع', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'value' => '0500000000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'value' => 'info@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'address', 'value' => 'حي الروضة - جدة', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp', 'value' => '0500000000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook', 'value' => 'facebook.com/example', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_schema_is_valid_json_on_core_pages(): void
    {
        $this->seedSettings();

        $service = Service::create([
            'title' => 'دهانات داخلية',
            'slug' => 'interior-paint',
            'description' => '<p>تفاصيل خدمة</p>',
            'active' => true,
        ]);

        $project = Project::create([
            'title' => 'مشروع فيلا',
            'slug' => 'villa-project',
            'description' => '<p>تفاصيل مشروع</p>',
            'active' => true,
            'service_id' => $service->id,
        ]);

        $post = BlogPost::create([
            'title' => 'مقال تجريبي',
            'slug' => 'sample-post',
            'content' => '<p>محتوى المقال</p>',
            'active' => true,
            'published_at' => now(),
        ]);

        Faq::create([
            'question' => 'سؤال 1؟',
            'answer' => 'إجابة 1',
            'blog_post_id' => $post->id,
            'active' => true,
        ]);

        Testimonial::create([
            'client_name' => 'عميل 1',
            'content' => 'تجربة ممتازة',
            'rating' => 5,
            'active' => true,
        ]);

        $keyword = Keyword::create([
            'name' => 'دهانات جدة',
            'slug' => 'dhanat-jeddah',
            'normalized' => 'دهانات جدة',
            'locale' => 'ar',
            'active' => true,
            'description' => 'وصف للكلمة',
        ]);

        $service->keywords()->attach($keyword->id, ['context' => 'both']);
        $project->keywords()->attach($keyword->id, ['context' => 'both']);
        $post->keywords()->attach($keyword->id, ['context' => 'both']);

        $pages = [
            '/',
            '/about',
            '/contact',
            '/services',
            '/services/' . $service->slug,
            '/projects',
            '/projects/' . $project->slug,
            '/blog',
            '/blog/' . $post->slug,
            '/keywords/' . $keyword->slug,
            '/keywords/' . $keyword->slug . '?type=services',
        ];

        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $schema = $this->extractSchema($html);

            $org = collect($schema['@graph'])->firstWhere('@id', url('/') . '/#organization');
            $this->assertNotEmpty($org);

            if ($url === '/') {
                $this->assertNotEmpty($org['sameAs'] ?? null, json_encode($org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                $this->assertContains('https://wa.me/0500000000', $org['sameAs']);
                $this->assertContains('https://facebook.com/example', $org['sameAs']);
            }
            foreach (($org['sameAs'] ?? []) as $sameAs) {
                $this->assertIsString($sameAs);
                $this->assertStringStartsWith('https://', $sameAs);
            }

            $website = collect($schema['@graph'])->firstWhere('@id', url('/') . '/#website');
            $this->assertNotEmpty($website);
            if ($url === '/') {
                $this->assertSame('SearchAction', $website['potentialAction'][0]['@type'] ?? null);
                $this->assertSame(url('/blog') . '?search={search_term_string}', $website['potentialAction'][0]['target']['urlTemplate'] ?? null);
                $this->assertSame('PropertyValueSpecification', $website['potentialAction'][0]['query-input']['@type'] ?? null);
                $this->assertSame('search_term_string', $website['potentialAction'][0]['query-input']['valueName'] ?? null);
            }

            $webPage = collect($schema['@graph'])->firstWhere('@id', url()->current());
            if (!empty($webPage)) {
                $this->assertSame(url('/') . '/#website', $webPage['isPartOf']['@id'] ?? null);
                $this->assertSame(url('/') . '/#organization', $webPage['about']['@id'] ?? null);

                $types = $webPage['@type'] ?? [];
                if (is_string($types)) {
                    $types = [$types];
                }
                $this->assertIsArray($types);

                if ($url === '/about') {
                    $this->assertContains('AboutPage', $types);
                    $this->assertSame(url('/') . '/#organization', $webPage['mainEntity']['@id'] ?? null);
                } elseif ($url === '/contact') {
                    $this->assertContains('ContactPage', $types);
                    $this->assertSame('ContactPoint', $webPage['mainEntity']['@type'] ?? null);
                } elseif ($url === '/services') {
                    $this->assertContains('CollectionPage', $types);
                    $this->assertSame(url()->current() . '#collection', $webPage['mainEntity']['@id'] ?? null);
                } elseif ($url === '/projects') {
                    $this->assertContains('CollectionPage', $types);
                    $this->assertSame(url()->current() . '#collection', $webPage['mainEntity']['@id'] ?? null);
                } elseif ($url === '/blog') {
                    $this->assertContains('CollectionPage', $types);
                    $this->assertSame(url()->current() . '#collection', $webPage['mainEntity']['@id'] ?? null);
                } elseif (str_starts_with($url, '/services/')) {
                    $this->assertContains('ItemPage', $types);
                    $this->assertSame(url()->current() . '#service', $webPage['mainEntity']['@id'] ?? null);
                } elseif (str_starts_with($url, '/projects/')) {
                    $this->assertContains('ItemPage', $types);
                    $this->assertSame(url()->current() . '#project', $webPage['mainEntity']['@id'] ?? null);
                } elseif (str_starts_with($url, '/blog/')) {
                    $this->assertContains('ItemPage', $types);
                    $this->assertSame(url()->current() . '#article', $webPage['mainEntity']['@id'] ?? null);
                } elseif (str_starts_with($url, '/keywords/')) {
                    $this->assertContains('CollectionPage', $types);
                    $this->assertSame(url()->current() . '#collection', $webPage['mainEntity']['@id'] ?? null);
                }

                $this->assertSame(url()->current() . '#primaryimage', $webPage['primaryImageOfPage']['@id'] ?? null);

                $image = collect($schema['@graph'])->firstWhere('@id', url()->current() . '#primaryimage');
                $this->assertNotEmpty($image);
                $this->assertSame('ImageObject', $image['@type'] ?? null);

                $person = collect($schema['@graph'])->first(function ($node) {
                    return is_array($node) && ($node['@type'] ?? null) === 'Person' && is_string($node['@id'] ?? null) && str_contains($node['@id'], '/#/schema/person/');
                });
                $this->assertNotEmpty($person);
            }
        }
    }

    public function test_keyword_type_services_schema_does_not_depend_on_image_fields(): void
    {
        $this->seedSettings();

        $service = Service::create([
            'title' => 'دهانات خارجية',
            'slug' => 'exterior-paint',
            'description' => '<p>تفاصيل خدمة</p>',
            'image_path' => null,
            'active' => true,
        ]);

        $keyword = Keyword::create([
            'name' => 'دهانات',
            'slug' => 'dhanat',
            'normalized' => 'دهانات',
            'locale' => 'ar',
            'active' => true,
        ]);

        $service->keywords()->attach($keyword->id, ['context' => 'both']);

        $html = $this->get('/keywords/' . $keyword->slug . '?type=services')->assertStatus(200)->getContent();
        $schema = $this->extractSchema($html);

        $collectionPage = collect($schema['@graph'])->firstWhere('@id', url()->current() . '#collection');
        $this->assertNotEmpty($collectionPage);
        $this->assertSame(url()->current() . '#itemlist', $collectionPage['mainEntity']['@id'] ?? null);

        $itemList = collect($schema['@graph'])->firstWhere('@id', url()->current() . '#itemlist');
        $this->assertNotEmpty($itemList);
        $this->assertSame('ItemList', $itemList['@type'] ?? null);

        $firstListItem = $itemList['itemListElement'][0]['item'] ?? null;
        $this->assertSame('Service', $firstListItem['@type'] ?? null);
        $this->assertSame($service->title, $firstListItem['name'] ?? null);
    }
}
