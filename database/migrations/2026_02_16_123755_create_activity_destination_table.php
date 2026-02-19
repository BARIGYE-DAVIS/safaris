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
        Schema::create('activity_destination', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('destination_id');

            // If you want extra columns later (leave commented for now)
            // $table->string('note')->nullable();
            // $table->integer('priority')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');

            $table->foreign('destination_id')
                ->references('id')
                ->on('destinations')
                ->onDelete('cascade');

            // Prevent duplicate pairs
            $table->unique(['activity_id', 'destination_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_destination');
    }
};