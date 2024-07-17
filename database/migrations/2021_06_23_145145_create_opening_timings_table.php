<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_timings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vendor_id');
            $table->string('sun_opening')->nullable();
            $table->string('sun_closing')->nullable();
            $table->string('mon_opening')->nullable();
            $table->string('mon_closing')->nullable();
            $table->string('tue_opening')->nullable();
            $table->string('tue_closing')->nullable();
            $table->string('wed_opening')->nullable();
            $table->string('wed_closing')->nullable();
            $table->string('thu_opening')->nullable();
            $table->string('thu_closing')->nullable();
            $table->string('fri_opening')->nullable();
            $table->string('fri_closing')->nullable();
            $table->string('sat_opening')->nullable();
            $table->string('sat_closing')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opening_timings');
    }
}
