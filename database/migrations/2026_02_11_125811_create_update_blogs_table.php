<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Add author_name instead of author_id foreign key
            if (!Schema::hasColumn('blogs', 'author_name')) {
                $table->string('author_name')->nullable()->after('tags');
            }
            
            if (!Schema::hasColumn('blogs', 'reading_time')) {
                $table->integer('reading_time')->nullable()->after('views_count');
            }
            
            if (!Schema::hasColumn('blogs', 'og_image')) {
                $table->string('og_image')->nullable()->after('meta_keywords');
            }
            
            if (!Schema::hasColumn('blogs', 'content_json')) {
                $table->longText('content_json')->nullable()->after('content');
            }
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['author_name', 'reading_time', 'og_image', 'content_json']);
        });
    }
};