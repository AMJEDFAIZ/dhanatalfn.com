<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasKeywords;

class BlogPost extends Model
{
    use HasKeywords;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'active',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {

            if (empty($post->slug)) {
                $base = Str::slug($post->title);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $post->slug = $slug;
            }
        });

        static::updating(function ($post) {
            if (empty($post->slug)) {
                $base = Str::slug($post->title);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->whereKeyNot($post->getKey())->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $post->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->where('active', true);
    }
}
