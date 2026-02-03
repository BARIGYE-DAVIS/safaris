<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tour_itinerary', function (Blueprint $table) {
            $table->id();
            // Foreign key reference to tours table
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->integer('day_number');
            $table->text('activity'); // Description of the day's activity
            $table->string('day_title', 255)->nullable();
            $table->string('accommodation', 255)->nullable();
            $table->string('meals', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_itinerary');
    }
};