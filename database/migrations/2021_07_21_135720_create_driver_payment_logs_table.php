<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('payment_mode');
            $table->string('token')->nullable();
            $table->string('bill_amt')->comment("Total Bill Amount");
            $table->boolean('verified')->default(0);
            $table->string('ip')->nullable();
            $table->text('agent')->nullable();
            $table->text('action')->nullable();
            $table->string('type')->nullable();
            $table->text('response')->nullable();
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
        Schema::dropIfExists('driver_payment_logs');
    }
}
