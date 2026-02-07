<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = static::generateUniqueSlug($model, $model->title);
            }
        });

        static::updating(function (Model $model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = static::generateUniqueSlug($model, $model->title, $model->getKey());
            }
        });
    }

    public static function generateUniqueSlug(Model $model, string $title, $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (
            $model->newQuery()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where($model->getKeyName(), '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
