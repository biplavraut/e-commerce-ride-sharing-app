<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedBigInteger('rental_package_id')->nullable();
            $table->string('from')->nullable();
            $table->text('from_lat')->nullable();
            $table->text('from_long')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('payment_mode')->nullable();
            $table->unsignedInteger('price');
            $table->enum('status', ['pending', 'paused', 'ongoing', 'arrived', 'started', 'cancelled', 'completed'])->default('pending');
            $table->text('logs')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rental_package_id')->references('id')->on('rental_packages')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rental_trips');
    }
}
