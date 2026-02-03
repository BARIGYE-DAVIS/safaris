<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id(); // id INT, auto_increment, PK
            $table->string('username', 100);
            $table->string('password', 255);
            $table->string('role', 50)->default('admin');
            $table->string('email', 255)->unique();
            $table->string('contact', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('verification_code', 10)->nullable();
            $table->boolean('is_verified')->default(0);
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}