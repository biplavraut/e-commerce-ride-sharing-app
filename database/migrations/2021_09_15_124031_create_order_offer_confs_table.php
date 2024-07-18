<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOfferConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_offer_confs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_title')->nullable();
            $table->integer('no_of_orders')->default(0);
            $table->integer('discount')->default(0);
            $table->tinyInteger('enabled')->default(0);
            $table->dateTime('from');
            $table->dateTime('to');
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
        Schema::dropIfExists('order_offer_confs');
    }
}
