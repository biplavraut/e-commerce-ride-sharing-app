<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDineInFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dine_in_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('user_id');
            $table->date('date');
            $table->time('time');
            $table->string('status')->default('pending');
            $table->unsignedInteger('people_attend')->nullable();
            $table->string('total_price')->nullable();
            $table->string('bill')->nullable();
            $table->string('reward')->nullable();
            $table->text('special_instruction')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dine_in_forms');
    }
}
