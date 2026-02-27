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

    /** أسئلة شائعة فعّالة فقط (للعرض في الموقع) */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'project_id')->where('active', true)->orderBy('id');
    }

    /** كل الأسئلة الشائعة (للوحة التحكم) */
    public function allFaqs()
    {
        return $this->hasMany(Faq::class, 'project_id')->orderBy('id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
