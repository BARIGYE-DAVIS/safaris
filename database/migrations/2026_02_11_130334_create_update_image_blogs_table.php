<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blog_images', function (Blueprint $table) {
            // Add soft deletes to match blogs table
            if (!Schema::hasColumn('blog_images', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
            
            // Add index for better performance
            $table->index(['blog_id', 'type', 'order']);
        });
    }

    public function down()
    {
        Schema::table('blog_images', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['blog_id', 'type', 'order']);
        });
    }
};