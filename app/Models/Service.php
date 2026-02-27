<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;
use App\Traits\HasKeywords;

class Service extends Model
{
    use HasSlug, HasKeywords;

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

    /** أسئلة شائعة فعّالة فقط (للعرض في الموقع) */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'service_id')->where('active', true)->orderBy('id');
    }

    /** كل الأسئلة الشائعة (للوحة التحكم) */
    public function allFaqs()
    {
        return $this->hasMany(Faq::class, 'service_id')->orderBy('id');
    }

    public function iconClass(): ?string
    {
        if (!is_string($this->icon) || $this->icon === '') {
            return null;
        }

        $icon = trim($this->icon);

        if (!preg_match('/^fa-[a-z0-9-]+$/i', $icon)) {
            return null;
        }

        return strtolower($icon);
    }
}
