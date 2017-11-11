<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table){
            $table->increments('job_id');
            $table->string('job_num');
            $table->integer('order_id')->unsigned()->index();
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->string('section');
            $table->string('make');
            $table->string('product_item_code');
            $table->foreign('product_item_code')->references('product_item_code')->on('products');
            $table->string('description', 255);
            $table->string('drawing_no')->nullable();
            $table->string('other_attachment')->nullable();
            $table->integer('product_quantity')->unsigned();
            $table->integer('product_rate')->unsigned();
            $table->integer('discount')->unsigned();
            $table->date('due_date');
            $table->date('delivery_date');
            $table->string('challan_no');
            $table->string('lr_no');
            $table->tinyInteger('late_delivery')->default(0);
            $table->string('status', 255)->comment = "job currently at";
            $table->string('comment', 255)->default("");
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
        Schema::dropIfExists('jobs');
    }
}
