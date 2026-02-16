<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'normalized',
        'locale',
        'active',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function services()
    {
        return $this->morphedByMany(Service::class, 'keywordable')
            ->withPivot(['context', 'is_primary', 'weight'])
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'keywordable')
            ->withPivot(['context', 'is_primary', 'weight'])
            ->withTimestamps();
    }

    public function blogPosts()
    {
        return $this->morphedByMany(BlogPost::class, 'keywordable')
            ->withPivot(['context', 'is_primary', 'weight'])
            ->withTimestamps();
    }

    public function seoPages()
    {
        return $this->morphedByMany(SeoPage::class, 'keywordable')
            ->withPivot(['context', 'is_primary', 'weight'])
            ->withTimestamps();
    }
}
