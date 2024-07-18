<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('type')->nullable();
            $table->string('heard_from');
            $table->string('password');
            $table->string('image', 500)->nullable();
            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->string('partnership_type')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('area')->nullable();
            $table->text('lat')->nullable();
            $table->text('long')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('order_offer_applicable')->default(true);
            $table->string('email_token', 11)->nullable();
            $table->string('settlement_time')->default(30);
            $table->string('radius_limit')->nullable();
            $table->string('from')->default('app');
            $table->boolean('takeaway')->default(false);
            $table->boolean('dine_in')->default(false);
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
        Schema::dropIfExists('vendors');
    }
}
