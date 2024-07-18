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
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('total');
            $table->unsignedInteger('paying_total')->default(0);
            $table->string('location');
            $table->text('lat');
            $table->text('long');
            // $table->dateTime('preferred_deli_time')->nullable();
            // $table->dateTime('preferred_deli_date')->nullable();
            $table->string('order_by');
            $table->string('phone');
            $table->string('email');
            $table->boolean('accepted')->default(false);
            $table->enum('payment_mode', ['esewa', 'khalti', 'cash on delivery', 'himalayan bank', 'imepay', 'gogoWallet'])->nullable();
            $table->boolean('settle_status')->default(false);
            $table->dateTime('settlement_date')->nullable();
            $table->unsignedInteger('settle_id')->nullable();
            $table->unsignedInteger('settle_by')->nullable();
            $table->string('status')->comment('CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED');
            $table->dateTime('date');
            $table->string('delivery_location')->nullable();
            $table->unsignedInteger('ref_number')->nullable();
            $table->unsignedInteger('refundable_amount')->default(0);
            $table->text('nearest_landmark')->nullable();
            $table->boolean('takeaway')->default(false);
            $table->string('otp', 4)->nullable();
            $table->text('special_instruction')->nullable();
            $table->string('alternate_name')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('settle_id')->references('id')->on('vendor_settle_logs');
            $table->foreign('settle_by')->references('id')->on('admins');
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
