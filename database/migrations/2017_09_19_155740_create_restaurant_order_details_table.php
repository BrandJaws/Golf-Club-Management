<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_order_id');
            $table->bigInteger('restaurant_product_id');
            $table->bigInteger('quantity');
            $table->decimal('sale_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_order_details');
    }
}
