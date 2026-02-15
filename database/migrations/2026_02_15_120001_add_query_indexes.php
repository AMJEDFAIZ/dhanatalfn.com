<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->index(['active', 'sort_order'], 'services_active_sort_order_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index(['active', 'sort_order'], 'projects_active_sort_order_idx');
            $table->index(['service_id', 'active', 'sort_order'], 'projects_service_active_sort_order_idx');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->index(['active', 'sort_order'], 'skills_active_sort_order_idx');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index(['active', 'sort_order'], 'testimonials_active_sort_order_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->index(['project_id', 'active', 'sort_order'], 'gallery_project_active_sort_order_idx');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['active', 'published_at'], 'blog_posts_active_published_at_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index(['is_read', 'created_at'], 'messages_is_read_created_at_idx');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->index(['blog_post_id', 'active'], 'faqs_blog_post_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_active_sort_order_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_active_sort_order_idx');
            $table->dropIndex('projects_service_active_sort_order_idx');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropIndex('skills_active_sort_order_idx');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('testimonials_active_sort_order_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex('gallery_project_active_sort_order_idx');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_active_published_at_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_is_read_created_at_idx');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('faqs_blog_post_active_idx');
        });
    }
};
