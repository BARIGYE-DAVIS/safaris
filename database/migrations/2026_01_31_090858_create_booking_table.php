<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('country');
            $table->string('whatsapp');
            $table->string('group_size');
            $table->date('travel_date');
            $table->text('message')->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('travel_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking');
    }
};