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
			$table->string('enquiry_no')->index();
//            $table->foreign('enquiry_no')->references('enquiry_no')->on('enquiry');
            $table->string('product_name');
            $table->string('product_item_code')->index();
            $table->string('product_quantity');
            $table->string('product_rate')->default('');
            $table->integer('unit_id')->unsigned()->index();
			$table->foreign('unit_id')->references('unit_id')->on('units');
            $table->string('drawing_no');
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
