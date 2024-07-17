<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('driver_id');
            $table->string('reg_no');
            $table->string('type')->comment('Vehicle Type');
            $table->string('plate_no')->nullable()->comment('Vehicle Plate No');
            $table->string('manufacturing_year')->nullable();
            $table->string('license_category');
            $table->string('license_no');
            $table->date('license_expiry')->nullable();
            $table->string('license');
            $table->string('blue_book');
            $table->date('bluebook_expiry')->nullable();
            $table->string('color')->nullable()->comment('Vehicle Plate No');
            $table->string('picture');

            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_vehicles');
    }
}