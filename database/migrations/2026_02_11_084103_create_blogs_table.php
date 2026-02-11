<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('featured_image')->nullable(); // Store path instead of binary
            $table->text('excerpt')->nullable(); // Short summary for listing pages
            $table->longText('content'); // Main content with HTML
            $table->longText('content_json')->nullable(); // For editor.js or similar
            
            // SEO Fields
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('og_image')->nullable(); // Open Graph image for social sharing
            
            // Categorization & Tagging
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
            $table->string('tags')->nullable(); // Comma-separated tags
            
            // Author & Status
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // Analytics
            $table->integer('views_count')->default(0);
            $table->integer('reading_time')->nullable(); // in minutes
            
            $table->timestamps();
            $table->softDeletes(); // For trash functionality
            
            // Indexes for performance
            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};