<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'blog_post_id',
        'active',
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }
}
