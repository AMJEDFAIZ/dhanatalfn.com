<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;

class Service extends Model
{
    use HasSlug;
   
    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'image_path',
        'active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'service_id');
    }
}
