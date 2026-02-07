<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'client_name',
        'position',
        'content',
        'image_path',
        'rating',
        'active',
        'sort_order',
    ];
}
