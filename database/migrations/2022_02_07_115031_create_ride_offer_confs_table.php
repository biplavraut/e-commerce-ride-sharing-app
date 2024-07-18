<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideOfferConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_offer_confs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('offer_title')->nullable();
            $table->integer('no_of_rides')->default(0);
            $table->integer('discount')->default(0);
            $table->tinyInteger('enabled')->default(0);
            $table->dateTime('from');
            $table->dateTime('to');
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
        Schema::dropIfExists('ride_offer_confs');
    }
}
