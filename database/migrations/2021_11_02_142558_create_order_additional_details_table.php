<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAdditionalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_additional_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('order_ref_number')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->string('shipping_charge')->nullable();
            $table->string('gogo_reward_redeem')->nullable();
            $table->string('order_total')->nullable();
            $table->string('donation')->default(0);
            $table->string('order_cashback')->nullable();
            $table->string('total_cancelled')->default(0);
            $table->string('total_delivered')->default(0);
            $table->string('total_collected')->default(0);
            $table->string('total_refunded')->default(0);
            $table->enum('status', ['PENDING', 'DELIVERED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_additional_details');
    }
}
