<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إضافة دعم الأسئلة الشائعة للخدمات والمشاريع (إكمال الخيار أ).
     */
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('blog_post_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->after('service_id')->constrained('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['project_id']);
        });
    }
};
