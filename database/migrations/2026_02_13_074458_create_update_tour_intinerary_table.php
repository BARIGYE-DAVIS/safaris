<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NOTE: All new columns are nullable so running this migration WILL NOT DELETE
     * existing data in the table. It only adds nullable columns and optional indexes/foreign keys.
     */
    public function up(): void
    {
        Schema::table('tour_itinerary', function (Blueprint $table) {
            // Stores ordered blocks (text/image/gallery) as JSON/long text
            $table->longText('content_blocks')->nullable()->after('meals');

            // Optional quick cover/reference to a media row (media table may be created separately)
            $table->unsignedBigInteger('cover_media_id')->nullable()->after('content_blocks');
            $table->string('cover_caption')->nullable()->after('cover_media_id');

            // Optional audit: who last updated this itinerary day (references admins table if present)
            $table->unsignedBigInteger('updated_by')->nullable()->after('cover_caption');

            // Indexes for lookup performance
            $table->index('cover_media_id');
            $table->index('updated_by');
        });

        // Add foreign keys conditionally if referenced tables exist.
        // This avoids migration failure when those tables are not yet present.
        if (Schema::hasTable('tour_itinerary_media')) {
            Schema::table('tour_itinerary', function (Blueprint $table) {
                $table->foreign('cover_media_id')
                      ->references('id')
                      ->on('tour_itinerary_media')
                      ->nullOnDelete();
            });
        }

        // Use admins table for the updater reference (only add FK if admins table exists).
        if (Schema::hasTable('admins')) {
            Schema::table('tour_itinerary', function (Blueprint $table) {
                $table->foreign('updated_by')
                      ->references('id')
                      ->on('admins')
                      ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Dropping these columns will remove data stored in them; take care when rolling back.
     */
    public function down(): void
    {
        Schema::table('tour_itinerary', function (Blueprint $table) {
            // Attempt to drop foreign keys safely (ignore if they don't exist)
            try {
                $table->dropForeign(['cover_media_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->dropForeign(['updated_by']);
            } catch (\Throwable $e) {
                // ignore
            }

            // Drop indexes if present
            try { $table->dropIndex(['cover_media_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['updated_by']); } catch (\Throwable $e) {}

            // Drop the added columns
            if (Schema::hasColumn('tour_itinerary', 'content_blocks')) {
                $table->dropColumn('content_blocks');
            }
            if (Schema::hasColumn('tour_itinerary', 'cover_media_id')) {
                $table->dropColumn('cover_media_id');
            }
            if (Schema::hasColumn('tour_itinerary', 'cover_caption')) {
                $table->dropColumn('cover_caption');
            }
            if (Schema::hasColumn('tour_itinerary', 'updated_by')) {
                $table->dropColumn('updated_by');
            }
        });
    }
};