<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('heard_from')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('phone', 10)->nullable()->unique();
            $table->string('password');
            $table->string('image', 500)->nullable();
            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->string('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('interested_in')->nullable();
            $table->text('lat')->nullable();
            $table->text('long')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('rider')->default(false);
            $table->boolean('ondemand')->default(false);
            $table->integer('blacklisted')->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->string('email_token', 11)->nullable();
            $table->string('refer_code', 50)->nullable();
            $table->string('used_code', 50)->nullable();
            $table->string('subscription', 50)->nullable();
            $table->string('from')->default('app');
            $table->integer('reward_point')->default(0);
            $table->datetime('last_login_at')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
