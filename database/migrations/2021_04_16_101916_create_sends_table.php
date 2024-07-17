<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('pickup_location_name');
            $table->string('delivery_location_name');
            $table->double('pickup_location_lat');
            $table->double('pickup_location_long');
            $table->double('delivery_destination_lat');
            $table->double('delivery_destination_long');
            $table->double('distance_in_km');
            $table->string('delivery_item_type');
            $table->string('delivery_item_weight');
            $table->string('moneytery_value_of_item')->nullable();
            $table->double('generated_total_price');
            $table->double('discount_price')->nullable();
            $table->string('discount_method')->nullable();
            $table->double('net_total_price');
            $table->string('pickup_person_name');
            $table->string('pickup_point_address');
            $table->string('pickup_person_number');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_comment');
            $table->string('contact_person_name');
            $table->string('contact_person_address');
            $table->string('contact_person_number');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->string('delivery_comment');
            $table->tinyInteger('notify_receipents_by_sms')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('extra_column')->nullable();
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
        Schema::dropIfExists('sends');
    }
}
