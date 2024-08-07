<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug')->unique();
			$table->string('image')->nullable();
			$table->integer('order')->nullable();
			$table->string('subtitle')->nullable();
			$table->unsignedInteger('parent_id')->nullable();
			$table->boolean('parent')->default(false);
			$table->boolean('enabled')->default(true);
			$table->boolean('ondemand')->default(false);
			$table->boolean('opening_closing_time')->default(false);
			$table->boolean('show_product_category')->default(true);

			$table->timestamps();

			$table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('categories');
	}
}
