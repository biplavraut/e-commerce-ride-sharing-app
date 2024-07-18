<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoolRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('requested_user_id'); 
            $table->unsignedBigInteger('requested_pool_id'); 
            $table->string('remarks'); 
            $table->tinyInteger('status'); 
            $table->timestamps();
            $table->foreign('requested_user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('requested_pool_id')->references('id')->on('pools')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pool_request');
    }
}
