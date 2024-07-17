<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->string('image', 500)->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->enum('type', ['superadmin', 'admin', 'officer', 'support', 'content-writer', 'ride-team', 'delivery-team'])->default('admin');
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
