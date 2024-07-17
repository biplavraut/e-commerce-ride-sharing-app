<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('image', 300)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('phone')->nullable();
            $table->string('social_id')->nullable();
            $table->enum('social_from', ['facebook', 'google', 'linkedin', 'normal'])->default('normal');
            $table->boolean('phone_verified')->default(0);
            $table->boolean('verified')->default(0);
            $table->string('email_token', 11)->nullable();
            $table->string('access_token', 1500)->nullable();
            $table->string('refresh_token', 1500)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('heard_from')->nullable();
            $table->string('office')->nullable();
            $table->string('address')->nullable();
            $table->text('lat')->nullable();
            $table->text('long')->nullable();
            $table->string('phone1')->nullable();
            $table->string('refer_code', 50)->nullable();
            $table->string('used_code', 50)->nullable();
            $table->integer('reward_point')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
