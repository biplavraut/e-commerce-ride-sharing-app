<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaunchpadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('launchpads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('launchpad_category_id')->nullable();
            $table->string('name');
            $table->string('image');
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('hide')->default(0);
            $table->unsignedTinyInteger('order')->default(99);
            $table->timestamps();

            $table->foreign('launchpad_category_id')->references('id')->on('launchpad_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('launchpads');
    }
}
