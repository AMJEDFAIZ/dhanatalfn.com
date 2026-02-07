<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\GalleryImage;
use App\Models\Message;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'services' => Service::count(),
            'projects' => Project::count(),
            'gallery' => GalleryImage::count(),
            'blog' => BlogPost::count(),
            'testimonials' => Testimonial::count(),
            'skills' => Skill::count(),
        ];
        $total = array_sum($counts) ?: 1;
        $percentages = array_map(function ($c) use ($total) {
            return round(($c / $total) * 100, 2);
        }, $counts);
        $latest = [
            'services' => Service::orderBy('created_at', 'desc')->take(3)->get(),
            'projects' => Project::orderBy('created_at', 'desc')->take(3)->get(),
            'blog' => BlogPost::orderBy('created_at', 'desc')->take(3)->get(),
            'gallery' => GalleryImage::orderBy('created_at', 'desc')->take(3)->get(),
        ];
        $previews = [
            'services' => Service::orderBy('created_at', 'desc')->take(5)->get(),
            'projects' => Project::orderBy('created_at', 'desc')->take(5)->get(),
            'blog' => BlogPost::orderBy('created_at', 'desc')->take(5)->get(),
            'gallery' => GalleryImage::orderBy('created_at', 'desc')->take(5)->get(),
        ];
        $messageCounts = [
            'all' => Message::count(),
            'unread' => Message::where('is_read', false)->count(),
            'read' => Message::where('is_read', true)->count(),
            'by_subject' => [
                'استشارة' => Message::where('subject', 'استشارة')->count(),
                'عرض سعر' => Message::where('subject', 'عرض سعر')->count(),
                'دعم' => Message::where('subject', 'دعم')->count(),
                'أخرى' => Message::where('subject', 'أخرى')->count(),
            ],
        ];
        $unreadMessages = Message::where('is_read', false)->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'counts',
            'percentages',
            'latest',
            'previews',
            'messageCounts',
            'unreadMessages'
        ));
    }
}
