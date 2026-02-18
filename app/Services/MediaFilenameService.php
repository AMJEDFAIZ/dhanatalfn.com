<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFilenameService
{
    public function normalizeName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '';
        }

        $name = preg_replace('/\s+/u', ' ', $name);
        return trim((string) $name);
    }

    public function slugify(string $name): string
    {
        $name = $this->normalizeName($name);
        if ($name === '') {
            return '';
        }

        $slug = mb_strtolower($name, 'UTF-8');
        $slug = preg_replace('/[\x{0640}]/u', '', $slug);
        $slug = preg_replace('/[\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}]+/u', '', $slug);
        $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s_-]+/u', '', $slug);
        $slug = preg_replace('/[\s_]+/u', '-', $slug);
        $slug = preg_replace('/-+/u', '-', $slug);
        $slug = trim($slug, '-');

        if ($slug === '') {
            $slug = Str::slug($name);
            $slug = preg_replace('/-+/u', '-', (string) $slug);
            $slug = trim((string) $slug, '-');
        }

        return (string) $slug;
    }

    public function uniqueFilename(string $directory, string $base, string $extension, string $disk = 'public'): string
    {
        $directory = trim($directory, '/');
        $base = trim($base);
        $extension = ltrim(trim($extension), '.');

        $base = $this->slugify($base);
        if ($base === '') {
            $base = 'image';
        }

        $base = $this->limitBaseLength($base, 160);

        $storage = Storage::disk($disk);
        if ($directory !== '') {
            $storage->makeDirectory($directory);
        }

        $relative = $directory !== '' ? ($directory . '/' . $base . '.' . $extension) : ($base . '.' . $extension);
        if (!$storage->exists($relative)) {
            return $base . '.' . $extension;
        }

        $i = 2;
        while (true) {
            $suffix = '-' . $i;
            $candidateBase = $this->limitBaseLength($base, max(1, 160 - mb_strlen($suffix, 'UTF-8')));
            $candidate = $candidateBase . $suffix;
            $relative = $directory !== '' ? ($directory . '/' . $candidate . '.' . $extension) : ($candidate . '.' . $extension);
            if (!$storage->exists($relative)) {
                return $candidate . '.' . $extension;
            }
            $i++;
        }
    }

    public function uniqueWebpFilename(string $directory, string $title, string $fallbackBase = 'image', string $disk = 'public'): string
    {
        $base = $this->slugify($title);
        if ($base === '') {
            $base = $this->slugify($fallbackBase);
        }

        return $this->uniqueFilename($directory, $base, 'webp', $disk);
    }

    private function limitBaseLength(string $base, int $maxChars): string
    {
        $base = trim($base, '-');
        if ($base === '') {
            return 'image';
        }

        if (mb_strlen($base, 'UTF-8') <= $maxChars) {
            return $base;
        }

        $base = mb_substr($base, 0, $maxChars, 'UTF-8');
        $base = preg_replace('/-+$/u', '', (string) $base);
        $base = trim((string) $base, '-');

        return $base !== '' ? $base : 'image';
    }
}
