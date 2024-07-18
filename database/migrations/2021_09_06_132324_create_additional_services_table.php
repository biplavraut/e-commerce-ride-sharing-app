<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
			$table->string('slug')->unique();
			$table->string('image')->nullable();
			$table->integer('order')->default(99)->nullable();
			$table->string('subtitle')->nullable();
			$table->string('cashback')->nullable();
			$table->boolean('enabled')->default(true);
            $table->boolean('enabled_promo')->default(false);
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
        Schema::dropIfExists('additional_services');
    }
}
