<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('marketplace_id')->nullable();
            $table->string('product_name')->nullable();
            $table->double('current_price')->nullable();
            $table->string('currency')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('categories')->nullable();
            $table->string('product_url')->nullable();
            $table->timestamp('product_listing_start_time')->nullable();
            $table->timestamp('product_listing_end_time')->nullable();
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
        Schema::dropIfExists('products');
    }
}
