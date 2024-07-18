<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('code', 100)->unique()->nullable();
            $table->string('slug', 100)->unique();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('opening_stock');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percent', 'amount'])->nullable();
            $table->integer('discount')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('batch_no')->nullable();
            $table->date('expire_date')->nullable();
            $table->float('vat_percentage')->nullable();
            $table->float('service_charge_percentage')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('hide')->nullable();
            $table->timestamps();

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_updates');
    }
}
