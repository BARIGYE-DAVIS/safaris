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
        Schema::create('custom_tour_requests', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('country');
            $table->string('language')->nullable();
            
            // Travel Details
            $table->date('travel_date_from')->nullable();
            $table->date('travel_date_to')->nullable();
            $table->boolean('flexible_dates')->default(false);
            $table->string('duration')->nullable(); // e.g., "5-7 days"
            $table->integer('adults_count')->default(1);
            $table->integer('children_count')->default(0);
            $table->integer('infants_count')->default(0);
            
            // Tour Preferences
            $table->string('budget_category')->nullable(); // budget, mid-range, luxury, ultra-luxury
            $table->json('destinations')->nullable(); // Array of destination IDs
            $table->json('activities')->nullable(); // Array of activity IDs
            $table->string('accommodation_preference')->nullable();
            
            // Special Requirements
            $table->json('special_requirements')->nullable(); // Array of special needs
            $table->text('dietary_restrictions')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('special_requests')->nullable();
            
            // Additional Info
            $table->string('heard_from')->nullable();
            $table->string('approximate_budget')->nullable();
            
            // Status & Admin
            $table->enum('status', ['new', 'contacted', 'quoted', 'booked', 'cancelled'])->default('new');
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tour_requests');
    }
};