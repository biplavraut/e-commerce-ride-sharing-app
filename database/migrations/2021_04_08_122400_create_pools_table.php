<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('current_location');
            $table->string('desire_destination');
            $table->double('location_lat');
            $table->double('location_long');
            $table->double('destination_lat');
            $table->double('destination_long');
            $table->date('date');
            $table->time('time');
            $table->double('distance_in_km');
            $table->integer('required_seat');
            $table->string('vechical_type');
            $table->boolean('is_recurring')->default(0);
            $table->dateTime('recurring_strat_date');
            $table->dateTime('recurring_end_date');
            $table->enum('pool_type',['request','offer']);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('pools');
    }
}
