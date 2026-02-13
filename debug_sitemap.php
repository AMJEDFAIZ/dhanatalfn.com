<?php

use App\Models\Service;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

echo "Checking Services...\n";
$s = Service::where('active', true)->first();
if ($s) {
    echo "Service: {$s->title}\n";
    echo "Image Path: " . ($s->image_path ?? 'NULL') . "\n";
    echo "Main Image: " . ($s->main_image ?? 'NULL') . "\n";
}

echo "\nChecking Projects...\n";
$p = Project::where('active', true)->first();
if ($p) {
    echo "Project: {$p->title}\n";
    echo "Main Image: " . ($p->main_image ?? 'NULL') . "\n";
    if (method_exists($p, 'images')) {
        echo "Gallery Images Count: " . $p->images()->count() . "\n";
        foreach ($p->images as $img) {
            echo " - Gallery Img: " . ($img->image_path ?? $img->path ?? 'NULL') . "\n";
        }
    }
}
