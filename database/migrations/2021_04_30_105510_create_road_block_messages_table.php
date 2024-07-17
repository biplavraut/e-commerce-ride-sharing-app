<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoadBlockMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('road_block_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->text("image")->nullable();
            $table->text("description");
            $table->tinyInteger('show_image_on_top')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('road_block_messages');
    }
}
