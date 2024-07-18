<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSettleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_settle_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->string('amount')->default(0);
            $table->text('log')->nullable();
            $table->unsignedInteger('admin_id')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_settle_logs');
    }
}
