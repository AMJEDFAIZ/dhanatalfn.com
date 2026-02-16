<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeoMetaService
{
    public function keywordsString(array $keywords): string
    {
        $keywords = collect($keywords)
            ->filter(fn($v) => is_string($v) && trim($v) !== '')
            ->map(fn($v) => trim($v))
            ->unique()
            ->values()
            ->all();

        return implode(', ', $keywords);
    }

    public function metaForModel(Model $model, array $options = []): array
    {
        $title = $options['title'] ?? ($model->meta_title ?? ($model->title ?? null));
        $description = $options['description'] ?? ($model->meta_description ?? null);
        $descriptionSource = $description;

        if (empty($descriptionSource)) {
            $attr = $model->getAttribute('description');
            if (is_string($attr) && trim($attr) !== '') {
                $descriptionSource = $attr;
            }
        }
        if (empty($descriptionSource)) {
            $attr = $model->getAttribute('content');
            if (is_string($attr) && trim($attr) !== '') {
                $descriptionSource = $attr;
            }
        }

        $description = !empty($descriptionSource)
            ? Str::limit(preg_replace('/\s+/u', ' ', strip_tags((string) $descriptionSource)), 160)
            : null;

        $keywords = [];
        if (method_exists($model, 'keywordsForMeta')) {
            $keywords = $model->keywordsForMeta(KeywordService::META_LIMIT);
        }

        if (empty($keywords) && !empty($options['fallback_keywords'])) {
            $keywords = (array) $options['fallback_keywords'];
        }

        if (empty($keywords) && is_string($title) && trim($title) !== '') {
            $keywords = [trim($title)];
        }

        return [
            'meta_title' => is_string($title) ? trim($title) : null,
            'meta_description' => $description,
            'meta_keywords' => $this->keywordsString($keywords),
        ];
    }
}
