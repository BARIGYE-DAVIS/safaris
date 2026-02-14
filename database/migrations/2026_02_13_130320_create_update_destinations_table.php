<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            // JSON column storing per-section content_blocks:
            // { "overview": [...], "activities": [...], ... }
            $table->json('sections_content')->nullable()->after('description');

            // Draft / publish support
            $table->boolean('is_draft')->default(false)->after('sections_content');
            $table->foreignId('draft_user_id')->nullable()->constrained('users')->nullOnDelete()->after('is_draft');
            $table->timestamp('published_at')->nullable()->after('draft_user_id');

            $table->index('is_draft');
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropIndex(['is_draft']);
            $table->dropColumn(['sections_content', 'is_draft', 'draft_user_id', 'published_at']);
        });
    }
};