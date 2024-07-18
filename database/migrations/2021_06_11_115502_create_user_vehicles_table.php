<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('vehicle_color')->comment('Vehicle Color');
            $table->string('main_type')->comment('Vehicle Main Type');
            $table->string('type')->comment('Vehicle sub');
            $table->string('reg_no');
            $table->integer('fuel_sharing_km')->nullable()->comment('Fuel Sharing per KM');
            $table->string('vehicle_image');
            $table->string('license_image');
            $table->integer('offering_seats')->comment('Total Number of available seats');
            $table->string('check_point')->nullable()->comment('Fuel Check Point');
            $table->string('features')->nullable()->comment('Fuel Check Point');
            $table->string('remarks')->nullable()->comment('Fuel Check Point');
            $table->tinyInteger('is_default')->default(1);
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_vehicles');
    }
}
