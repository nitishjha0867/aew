<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
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
			$table->increments('product_id');
			$table->string('product_name');
			$table->string('product_item_code')->index();
			$table->string('product_quantity');
			$table->string('product_rate');
			$table->string('unit_id')->index();
			$table->foreign('unit_id')->references('unit_id')->on('products');
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
    }
}
