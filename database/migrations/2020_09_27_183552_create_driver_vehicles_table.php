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
            $table->string('reg_no')->nullable();
            $table->string('type')->nullable()->comment('Vehicle Type');
            $table->string('plate_no')->nullable()->comment('Vehicle Plate No');
            $table->string('manufacturing_year')->nullable();
            $table->string('license_category')->nullable();
            $table->string('license_no')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('license')->nullable();
            $table->string('blue_book')->nullable();
            $table->string('blue_book_sec')->nullable();
            $table->string('blue_book_trd')->nullable();
            $table->date('bluebook_expiry')->nullable();
            $table->string('color')->nullable()->comment('Vehicle Plate No');
            $table->string('picture')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_contact')->nullable();

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
