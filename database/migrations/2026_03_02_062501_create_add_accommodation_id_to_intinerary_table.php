<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tour_itinerary', function (Blueprint $table) {
            // Add the foreign key to accommodations table
            $table->foreignId('accommodation_id')
                  ->nullable()
                  ->after('accommodation')
                  ->constrained('accommodations')
                  ->nullOnDelete();
            
            // We'll keep the old 'accommodation' text column for now
            // In case there's existing data, we won't break anything
            // Later we can remove it after migration is complete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_itinerary', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['accommodation_id']);
            $table->dropColumn('accommodation_id');
        });
    }
};