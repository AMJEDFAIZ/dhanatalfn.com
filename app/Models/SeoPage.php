<?php

namespace App\Models;

use App\Traits\HasKeywords;
use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    use HasKeywords;

    protected $fillable = [
        'key',
        'route_name',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
