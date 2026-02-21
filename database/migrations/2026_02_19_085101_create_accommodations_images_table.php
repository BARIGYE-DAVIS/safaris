<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('accommodation_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Image path (e.g. storage path)
            $table->string('path');

            // Optional caption / alt text
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();

            // Optional flag if you ever want another "featured" from this set
            $table->boolean('is_featured')->default(false);

            // For ordering images in gallery
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_images');
    }
};