<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title', 255); // Tour name/title
            $table->string('slug', 255)->unique(); // SEO-friendly URL
            $table->string('category', 255)->nullable(); // e.g., "Family", "Adventure", etc.
            $table->string('destinations', 255)->nullable(); // e.g., comma-separated: "Masai Mara, Nairobi"
            $table->string('type', 100)->nullable(); // e.g., "Private", "Group", etc.
            $table->text('description'); // Main description
            $table->text('included'); // What’s included in the tour
            $table->text('excluded'); // What’s not included
            // SEO fields
            $table->string('meta_keywords', 500)->nullable(); // For SEO
            $table->string('meta_description', 500)->nullable(); // For SEO
            $table->string('meta_title', 500)->nullable(); // Optional: For search snippet title
            // Main image (store url or file name)
            $table->string('featured_image')->nullable(); // Path or filename, not binary/blob
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tours');
    }
};