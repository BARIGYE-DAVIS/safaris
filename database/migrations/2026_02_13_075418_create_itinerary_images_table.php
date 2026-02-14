<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates a table to store images for individual itinerary days.
     * - Nullable fields are used where appropriate so this migration is safe to run against existing data.
     * - Foreign keys to other tables (admins) are added conditionally to avoid failures if those tables don't exist yet.
     */
    public function up(): void
    {
        Schema::create('tour_itinerary_images', function (Blueprint $table) {
            $table->id();

            // Reference to the itinerary day this image belongs to
            $table->foreignId('tour_itinerary_id')
                  ->constrained('tour_itinerary')
                  ->onDelete('cascade');

            // Optional block identifier (if using block-based content) to tie this image to a specific paragraph/block
            $table->string('block_id')->nullable();

            // Stored file locations (store paths, not binary data)
            $table->string('storage_path');               // e.g. "tours/42/itineraries/11/photo.jpg"
            $table->string('thumbnail_path')->nullable(); // e.g. "tours/.../thumbs/photo.jpg"

            // Descriptive metadata
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();

            // Ordering within a block or gallery
            $table->integer('order')->default(0);

            // Optional uploader/admin reference; FK added only if admins table exists
            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Useful indexes
            $table->index('tour_itinerary_id');
            $table->index('block_id');
            $table->index('uploaded_by');
        });

        // Add FK to admins if the table exists (avoid migration failures).
        if (Schema::hasTable('admins')) {
            Schema::table('tour_itinerary_images', function (Blueprint $table) {
                $table->foreign('uploaded_by')
                      ->references('id')
                      ->on('admins')
                      ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Dropping this table will remove stored media rows; ensure backups if needed.
     */
    public function down(): void
    {
        // Drop FK to admins if present (ignore errors)
        if (Schema::hasTable('tour_itinerary_images')) {
            try {
                Schema::table('tour_itinerary_images', function (Blueprint $table) {
                    $table->dropForeign(['uploaded_by']);
                });
            } catch (\Throwable $e) {
                // ignore if foreign key doesn't exist
            }
        }

        Schema::dropIfExists('tour_itinerary_images');
    }
};