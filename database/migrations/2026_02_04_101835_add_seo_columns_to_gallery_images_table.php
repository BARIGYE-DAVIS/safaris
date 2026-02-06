<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
            $table->string('alt_text')->nullable()->after('caption');
            $table->text('meta_description')->nullable()->after('alt_text');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->json('tags')->nullable()->after('meta_keywords');
            
            // Add indexes for better performance
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            
            $table->dropColumn([
                'slug',
                'alt_text',
                'meta_description',
                'meta_keywords',
                'tags'
            ]);
        });
    }
};