<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_confs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rider_nearby_radius')->default(5)->comment('For trip request');
            $table->string('rider_credit')->default(1000)->comment('For Rider');
            $table->string('free_delivery_after')->default(999)->comment('For Rider');
            $table->string('delivery_charge')->default(100);
            $table->string('delivery_charge_outside')->default(100);
            $table->time('night_surge_start')->default("22:00");
            $table->time('night_surge_end')->default("04:00");
            $table->string('first_download_reward')->default(0);
            $table->string('referral_user_point')->default(25);
            $table->string('referred_user_point')->default(50);
            $table->string('cashback_percent')->nullable();
            $table->string('purchase_total')->nullable();
            $table->string('rider_first_download_reward')->default(0);
            $table->string('rider_referral_user_point')->default(25);
            $table->string('rider_referred_user_point')->default(0);
            $table->string('utility_promo')->nullable();
            $table->string('dinein_cashback')->default(0);
            $table->string('rider_refer_limit')->default(20);
            $table->string('user_refer_limit')->default(250);
            $table->string('reward_redeem_limit')->default(15);
            $table->string('user_refer_text')->nullable();
            $table->string('pool_bike_per_km_per_seat')->default(10);
            $table->string('pool_car_per_km_per_seat')->default(5);

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
        Schema::dropIfExists('default_confs');
    }
}
