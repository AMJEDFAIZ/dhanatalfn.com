<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Service;
use App\Models\Project;
use App\Models\BlogPost;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sitemap generation...');

        $sitemap = Sitemap::create();

        // Static Pages
        // Home
        $sitemap->add(Url::create(route('home'))
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // About
        $sitemap->add(Url::create(route('about'))
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Contact
        $sitemap->add(Url::create(route('contact'))
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Services Index
        $sitemap->add(Url::create(route('services.index'))
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Projects Index
        $sitemap->add(Url::create(route('projects.index'))
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Blog Index
        $sitemap->add(Url::create(route('blog.index'))
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Dynamic Content: Services
        $this->info('Adding Services...');
        Service::where('active', true)->chunk(100, function ($services) use ($sitemap) {
            foreach ($services as $service) {
                $sitemap->add(Url::create(route('services.show', $service))
                    ->setLastModificationDate($service->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });

        // Dynamic Content: Projects
        $this->info('Adding Projects...');
        Project::where('active', true)->chunk(100, function ($projects) use ($sitemap) {
            foreach ($projects as $project) {
                $sitemap->add(Url::create(route('projects.show', $project))
                    ->setLastModificationDate($project->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });

        // Dynamic Content: Blog Posts
        $this->info('Adding Blog Posts...');
        BlogPost::where('active', true)
            ->whereDate('published_at', '<=', now())
            ->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(Url::create(route('blog.show', $post))
                        ->setLastModificationDate($post->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
                }
            });

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap generated successfully at {$path}");
    }
}
