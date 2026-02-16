<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywordables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')->constrained('keywords')->cascadeOnDelete();
            $table->morphs('keywordable');
            $table->string('context', 20)->default('both');
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('weight')->default(0);
            $table->timestamps();

            $table->unique(['keyword_id', 'keywordable_type', 'keywordable_id', 'context'], 'keywordables_unique');
            $table->index(['keywordable_type', 'keywordable_id'], 'keywordables_keywordable_index');
            $table->index(['keyword_id'], 'keywordables_keyword_id_index');
            $table->index(['context'], 'keywordables_context_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywordables');
    }
};
