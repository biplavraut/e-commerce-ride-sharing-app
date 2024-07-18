<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slider_text')->nullable();
            $table->string('image');
            $table->boolean('hide')->default(0);
            $table->unsignedInteger('order')->default(99);
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
        Schema::dropIfExists('website_sliders');
    }
}
