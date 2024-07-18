<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->string('from');
            $table->string('to');
            $table->text('from_lat')->nullable();
            $table->text('from_long')->nullable();
            $table->text('to_lat')->nullable();
            $table->text('to_long')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('otp')->nullable();
            $table->enum('status', ['pending', 'paused', 'ongoing', 'arrived', 'started', 'cancelled', 'delivered', 'collected'])->default('pending');
            $table->text('logs')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('deliveries');
    }
}
