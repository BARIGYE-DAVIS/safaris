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
        Schema::create('custom_tour_request_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_tour_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->integer('days_to_spend')->nullable();
            $table->integer('priority')->nullable(); // Order of visit
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent duplicate entries
            $table->unique(['custom_tour_request_id', 'destination_id'], 'ctr_dest_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tour_request_destinations');
    }
};