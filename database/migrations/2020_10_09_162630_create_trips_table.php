<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->string('from');
            $table->string('to');
            $table->text('from_lat')->nullable();
            $table->text('from_long')->nullable();
            $table->text('to_lat')->nullable();
            $table->text('to_long')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->unsignedInteger('price');
            $table->string('payment_mode')->nullable();
            $table->string('distance')->nullable();
            $table->string('otp')->nullable();
            $table->string('duration')->nullable();
            $table->string('booked_for')->nullable();
            $table->string('booked_for_no')->nullable();
            $table->enum('status', ['pending', 'scheduled', 'paused', 'ongoing', 'arrived', 'started', 'cancelled', 'disputed', 'accident', 'completed'])->default('pending');
            $table->text('logs')->nullable();
            $table->text('dispute')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->boolean('done')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->string('ref_number')->nullable();
            $table->string('donationAmount')->nullable();
            $table->boolean('surge')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('trips');
    }
}
