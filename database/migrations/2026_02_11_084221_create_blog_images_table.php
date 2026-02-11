<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->enum('type', ['inline', 'gallery', 'featured'])->default('inline');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_images');
    }
};