<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->binary('featured_image')->nullable(); // longblob
            $table->longText('content_json')->charset('utf8mb4')->collation('utf8mb4_bin');
            $table->string('meta_keywords', 500)->nullable();      // for SEO keywords (comma separated)
            $table->string('meta_description', 500)->nullable();   // for SEO description
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};