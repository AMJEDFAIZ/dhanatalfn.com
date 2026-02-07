<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['title', 'image_path', 'project_id', 'sort_order', 'active'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
