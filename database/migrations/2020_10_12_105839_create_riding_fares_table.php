<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidingFaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riding_fares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vehicle');
            $table->unsignedInteger('price');
            $table->unsignedInteger('flat_price')->default(0);
            $table->float('night_surge')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('riding_fares');
    }
}
