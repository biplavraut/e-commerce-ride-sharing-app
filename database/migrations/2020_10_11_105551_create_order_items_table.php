<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('gogo_price')->default(0);
            $table->unsignedInteger('elite_price')->default(0);
            $table->unsignedInteger('tax_amt')->default(0);
            $table->unsignedInteger('service_charge_amt')->default(0);
            $table->enum('discount_type', ['percent', 'amount'])->default('amount');
            $table->integer('discount');
            $table->integer('quantity');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('special_instruction')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
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
        Schema::dropIfExists('order_items');
    }
}
