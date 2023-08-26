<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 20);
            $table->string('last_name', 20)->nullable();
            $table->string('email', 50)->unique()->nullable();
            $table->string('dial_code', 10)->nullable();
            $table->string('mobile', 15)->unique()->nullable();
            $table->string('full_mobile', 20)->nullable();
            $table->string('password')->nullable();
            $table->string('username', 20)->unique()->nullable();// new
            $table->string('image')->nullable();// new
            $table->enum('gender', ['male', 'female', 'null'])->default('null');// new
            $table->enum('skill_level', ['A', 'B', 'C', 'D'])->default('D');// new
            $table->dateTime('birthday')->nullable();// new
            $table->boolean('notify')->default(true);// new
            $table->boolean('is_signed_up')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
