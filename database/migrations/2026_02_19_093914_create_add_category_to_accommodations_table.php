<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            // Add after "type" for clarity (adjust position if you like)
            $table->string('category', 50)
                  ->nullable()
                  ->after('type')
                  ->comment('budget, mid-range, high-end/luxury');
        });
    }

    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};