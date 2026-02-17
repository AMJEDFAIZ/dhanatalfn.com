<?php

namespace App\Traits;

use App\Models\Keyword;

trait HasKeywords
{
    public function keywords()
    {
        return $this->morphToMany(Keyword::class, 'keywordable')
            ->withPivot(['context', 'is_primary', 'weight'])
            ->withTimestamps();
    }

    public function metaKeywords()
    {
        return $this->keywords()
            ->wherePivotIn('context', ['meta', 'both'])
            ->orderByDesc('keywordables.is_primary')
            ->orderByDesc('keywordables.weight')
            ->orderBy('keywords.name');
    }

    public function contentKeywords()
    {
        return $this->keywords()
            ->wherePivotIn('context', ['content', 'both'])
            ->orderByDesc('keywordables.is_primary')
            ->orderByDesc('keywordables.weight')
            ->orderBy('keywords.name');
    }

    public function keywordsForMeta(int $limit = 12): array
    {
        return $this->metaKeywords()
            ->orderByDesc('keywordables.is_primary')
            ->orderByDesc('keywordables.weight')
            ->orderBy('keywords.name')
            ->limit($limit)
            ->pluck('keywords.name')
            ->filter()
            ->values()
            ->all();
    }
}
