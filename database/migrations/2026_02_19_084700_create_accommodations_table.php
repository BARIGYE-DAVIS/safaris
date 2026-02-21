<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name');
            $table->string('slug')->unique()->nullable();

            // Type: lodge, tented_camp, hotel, guesthouse, etc.
            $table->string('type')->nullable();

            // Location text (e.g. "Near Park A main gate", "City center")
            $table->string('location')->nullable();

            // Optional relation to a destination or country if you want to link them
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();

            // Pricing
            $table->string('currency', 10)->default('USD');
            $table->decimal('price_from', 10, 2)->nullable();
            $table->decimal('price_to', 10, 2)->nullable(); // optional upper range

            // Description
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();

            // Featured image (main image path)
            $table->string('featured_image')->nullable();

            // Amenities as JSON (list of strings)
            $table->json('amenities')->nullable();

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // For sorting if needed
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};