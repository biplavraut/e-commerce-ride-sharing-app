<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_category_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->string('title');
            $table->string('code', 100)->unique()->nullable();
            $table->string('slug', 100)->unique();
            $table->string('badge')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('price_1')->nullable();
            $table->float('elite_percent')->default(0);
            $table->unsignedInteger('opening_stock');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percent', 'amount'])->default('amount');
            $table->integer('discount')->default(0);
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->boolean('prescription_required');
            $table->unsignedInteger('batch_no')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('hide')->default(0);
            $table->boolean('verified')->default(0);
            $table->boolean('is_return')->nullable()->default(0);
            $table->string('return_days')->nullable();
            $table->float('vat_percentage')->default(0);
            $table->float('service_charge_percentage')->default(0);

            $table->timestamps();

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
