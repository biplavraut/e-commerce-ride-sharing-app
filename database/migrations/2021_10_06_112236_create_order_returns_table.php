<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket')->unique();
            $table->unsignedInteger('order_item_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('product_id');
            $table->string('reason');
            $table->integer('quantity')->nullable();
            $table->enum('status', ['requested', 'processing', 'accepted', 'declined', 'proceed-to-vendor', 'accepted-by-vendor', 'declined-by-vendor', 'cancelled', 'resolved'])->default('requested');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_returns');
    }
}
