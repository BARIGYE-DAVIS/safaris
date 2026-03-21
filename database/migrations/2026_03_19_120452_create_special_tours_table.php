<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_tours', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->string('slug', 255)->unique();

            $table->text('description');

            // Can store bullet lists / HTML
            $table->longText('whats_included')->nullable();
            $table->longText('whats_not_included')->nullable();

            // Pricing
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('UGX');
            $table->string('price_note', 255)->nullable(); // e.g. "per person", "from"

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::create('special_tour_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('special_tour_id')
                ->constrained('special_tours')
                ->cascadeOnDelete();

            $table->string('image_path'); // e.g. special-tours/abc.jpg
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_tour_images');
        Schema::dropIfExists('special_tours');
    }
};