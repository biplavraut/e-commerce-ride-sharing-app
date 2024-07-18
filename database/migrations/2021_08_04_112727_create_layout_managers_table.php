<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayoutManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('model_type');
            $table->string('model_id');
            $table->unsignedInteger('service_id');
            $table->integer('order')->default(99);
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
        Schema::dropIfExists('layout_managers');
    }
}
