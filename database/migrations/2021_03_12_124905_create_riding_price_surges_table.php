<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidingPriceSurgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riding_price_surges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('riding_fare_id');
            $table->string('title')->nullable();
            $table->dateTime('from');
            $table->dateTime('to');
            $table->unsignedInteger('price');
            $table->timestamps();

            $table->foreign('riding_fare_id')->references('id')->on('riding_fares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riding_price_surges');
    }
}
