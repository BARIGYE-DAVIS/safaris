<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destination_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('block_id')->nullable()->index(); // client block id (blk-...)
            $table->string('storage_path'); // e.g. destinations/{id}/sections/{section}/{file}
            $table->string('thumbnail_path')->nullable();
            $table->text('caption')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['destination_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_images');
    }
};