<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'blog_post_id',
        'service_id',
        'project_id',
        'active',
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
