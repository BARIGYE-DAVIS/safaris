<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tour_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->integer('group_size');
            $table->decimal('price', 10, 2); // Different price for each group size
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_prices');
    }
};