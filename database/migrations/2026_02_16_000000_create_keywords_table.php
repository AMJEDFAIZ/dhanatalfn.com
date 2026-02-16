<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('normalized', 255);
            $table->string('locale', 10)->default('ar');
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['locale', 'normalized']);
            $table->unique(['locale', 'slug']);
            $table->index(['active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
