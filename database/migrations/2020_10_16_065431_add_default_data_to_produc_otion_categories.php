<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultDataToProducOtionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //insert old data 
        $data   =   array('Top Generic', 'Top Brand', 'Best Seller', 'Featured','Prescription Required', 'Trending', 'Hot', 'Seasonal', 'Dear & Near','Offers','Deal of the day','Big Save', 'Top Picks', 'Send to others(Gift)', 'Explore Something New', 'Share & Earn', 'New Items', 'Stock Clearance', 'Hide');
        $order  =   1;
        foreach($data as $row):
            DB::table('product_option_categories')->insert(['title' => $row, 'slug' => Str::slug($row), 'order' => $order]);
            $order++;
        endforeach;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('product_option_categories')->delete();
    }
}
