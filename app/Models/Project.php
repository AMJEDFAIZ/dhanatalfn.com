<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;
use App\Traits\HasKeywords;

class Project extends Model
{
    use HasSlug, HasKeywords;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'scope',
        'duration',
        'main_image',
        'active',
        'sort_order',
        'meta_title',
        'meta_description',
        'service_id',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
