<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('vendor_option_category_id');
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('order')->default(99);
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_options');
    }
}
