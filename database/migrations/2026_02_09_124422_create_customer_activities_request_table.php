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
        Schema::create('custom_tour_request_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_tour_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->integer('priority')->nullable(); // Importance/preference level
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent duplicate entries
            $table->unique(['custom_tour_request_id', 'activity_id'], 'ctr_activity_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tour_request_activities');
    }
};