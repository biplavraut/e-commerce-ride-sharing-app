<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('total');
            $table->string('location');
            $table->text('lat');
            $table->text('long');
            $table->string('order_by');
            $table->string('phone');
            $table->string('email');
            $table->boolean('accepted')->default(false);
            $table->enum('payment_mode', ['esewa', 'khalti', 'cash on delivery', 'himalayan bank', 'imepay', 'gogo20 Cash'])->nullable();
            $table->string('status')->comment('CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED');
            $table->dateTime('date');
            $table->unsignedInteger('delivery_location')->nullable();
            $table->unsignedInteger('ref_number')->nullable();
            $table->unsignedInteger('refundable_amount')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
