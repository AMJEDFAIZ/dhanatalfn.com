<?php

namespace App\Services;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class KeywordService
{
    public const META_LIMIT = 12;
    public const CONTENT_LIMIT = 20;

    public function parseNames(?string $input): array
    {
        $input = is_string($input) ? trim($input) : '';
        if ($input === '') {
            return [];
        }

        $input = str_replace(["\r\n", "\r", "\n", '،', ';', '|'], ',', $input);
        $parts = array_map('trim', explode(',', $input));

        $out = [];
        foreach ($parts as $p) {
            $p = $this->normalizeName($p);
            if ($p !== '') {
                $out[] = $p;
            }
        }

        return array_values(array_unique($out));
    }

    public function normalizeName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '';
        }

        $name = preg_replace('/\s+/u', ' ', $name);
        $name = preg_replace('/[،,]+/u', ' ', $name);
        $name = preg_replace('/\s+/u', ' ', $name);

        return trim($name);
    }

    public function normalizeForUniqueness(string $name): string
    {
        $name = $this->normalizeName($name);
        if ($name === '') {
            return '';
        }

        $lower = mb_strtolower($name, 'UTF-8');
        // $lower = str_replace(['إ', 'أ', 'آ'], 'ا', $lower);
        // $lower = str_replace(['ى'], 'ي', $lower);
        // $lower = str_replace(['ة'], 'ه', $lower);
        $lower = preg_replace('/\s+/u', ' ', $lower);

        return trim($lower);
    }

    public function slugify(string $name): string
    {
        $name = $this->normalizeName($name);
        if ($name === '') {
            return '';
        }

        $slug = mb_strtolower($name, 'UTF-8');
        $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s_-]+/u', '', $slug);
        $slug = preg_replace('/[\s_]+/u', '-', $slug);
        $slug = preg_replace('/-+/u', '-', $slug);
        $slug = trim($slug, '-');

        if ($slug === '') {
            $slug = Str::slug($name);
        }

        return $slug;
    }

    public function findOrCreateManyByNames(array $names, string $locale = 'ar', ?int $userId = null): array
    {
        $ids = [];

        foreach ($names as $name) {
            $name = $this->normalizeName((string) $name);
            if ($name === '') {
                continue;
            }

            $normalized = $this->normalizeForUniqueness($name);
            if ($normalized === '') {
                continue;
            }

            $existing = Keyword::query()
                ->where('locale', $locale)
                ->where('normalized', $normalized)
                ->first();

            if ($existing) {
                $ids[] = (int) $existing->id;
                continue;
            }

            $slug = $this->slugify($name);
            if ($slug === '') {
                $slug = 'k-' . Str::lower(Str::random(10));
            }
            $slug = $this->ensureUniqueSlug($slug, $locale);

            $keyword = Keyword::create([
                'name' => $name,
                'slug' => $slug,
                'normalized' => $normalized,
                'locale' => $locale,
                'active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $ids[] = (int) $keyword->id;
        }

        return array_values(array_unique($ids));
    }

    public function prepareForSave(string $name, string $locale = 'ar', ?int $ignoreId = null): array
    {
        $name = $this->normalizeName($name);
        $normalized = $this->normalizeForUniqueness($name);

        $slug = $this->slugify($name);
        if ($slug === '') {
            $slug = 'k-' . Str::lower(Str::random(10));
        }
        $slug = $this->ensureUniqueSlug($slug, $locale, $ignoreId);

        return [
            'name' => $name,
            'normalized' => $normalized,
            'slug' => $slug,
            'locale' => $locale,
        ];
    }

    public function resolveIds(array $selectedIds, ?string $newNamesInput, int $limit, string $locale = 'ar', ?int $userId = null): array
    {
        $selectedIds = array_values(array_unique(array_map('intval', Arr::wrap($selectedIds))));
        $newNames = $this->parseNames($newNamesInput);

        $newIds = $this->findOrCreateManyByNames($newNames, $locale, $userId);

        $all = array_values(array_unique(array_merge($selectedIds, $newIds)));
        return $all;
    }

    public function resolveIdsOrFail(array $selectedIds, ?string $newNamesInput, int $limit, string $fieldKey, string $locale = 'ar', ?int $userId = null): array
    {
        $ids = $this->resolveIds($selectedIds, $newNamesInput, $limit, $locale, $userId);

        if (count($ids) > $limit) {
            throw ValidationException::withMessages([
                $fieldKey => "الحد الأعلى المسموح هو {$limit} كلمات.",
            ]);
        }

        return $ids;
    }

    public function syncContexts(Model $model, array $metaKeywordIds, array $contentKeywordIds, array $primaryKeywordIds = [], array $keywordWeights = []): void
    {
        $meta = array_values(array_unique(array_map('intval', $metaKeywordIds)));
        $content = array_values(array_unique(array_map('intval', $contentKeywordIds)));
        $primary = array_values(array_unique(array_map('intval', Arr::wrap($primaryKeywordIds))));
        $weights = is_array($keywordWeights) ? $keywordWeights : [];

        $sync = [];
        foreach ($meta as $id) {
            if ($id > 0) {
                $sync[$id] = ['context' => 'meta', 'is_primary' => false, 'weight' => 0];
            }
        }
        foreach ($content as $id) {
            if ($id <= 0) {
                continue;
            }
            if (isset($sync[$id])) {
                $sync[$id]['context'] = 'both';
            } else {
                $sync[$id] = ['context' => 'content', 'is_primary' => false, 'weight' => 0];
            }
        }

        foreach ($sync as $id => $pivot) {
            $isPrimary = in_array((int) $id, $primary, true);
            $w = $weights[$id] ?? 0;
            $w = is_numeric($w) ? (int) $w : 0;
            if ($w < 0) {
                $w = 0;
            }
            if ($w > 65535) {
                $w = 65535;
            }

            $sync[$id]['is_primary'] = $isPrimary;
            $sync[$id]['weight'] = $w;
        }

        $model->keywords()->sync($sync);
    }

    public function ensureUniqueSlug(string $slug, string $locale, ?int $ignoreId = null): string
    {
        $base = $slug;
        $candidate = $base;
        $i = 2;

        while (
            Keyword::query()
            ->where('locale', $locale)
            ->where('slug', $candidate)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $candidate = $base . '-' . $i;
            $i++;
        }

        return $candidate;
    }
}
