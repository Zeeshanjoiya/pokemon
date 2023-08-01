<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductsPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         //
         Schema::create('products_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('ProductID')->nullable();
            $table->string('product_last_sold_price')->nullable();
            $table->string('product_last_sold_date')->nullable();
            $table->double('product_last_sold_quantity')->nullable();
            $table->string('currency')->nullable();
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
        //
        Schema::dropIfExists('products_prices');
    }
}
