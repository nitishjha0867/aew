<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('enquiry', function (Blueprint $table) {
		$table->increments('sr_no');
		$table->string('quotation_no')->index();
		$table->integer('client_id')->unsigned()->index();
		$table->foreign('client_id')->references('client_id')->on('clients');
		$table->integer('plant_id')->unsigned()->index();
		$table->foreign('plant_id')->references('plant_id')->on('plants');
		$table->string('enquiry_no')->index();
		$table->integer('attachment_id')->unsigned()->index();
		$table->foreign('attachment_id')->references('attachment_id')->on('attachments');
		$table->integer('product_id')->unsigned()->index();
		$table->foreign('product_id')->references('product_id')->on('products');
		$table->date('enquiry_date')->index();
		$table->date('due_date')->index();
		$table->boolean('enquiry_submitted');
		$table->string('total_order_value');
		$table->string('negotitated_rate');
		$table->integer('contact_person')->unsigned()->index();
		$table->foreign('contact_person')->references('contact_person')->on('clients_contact');
		$table->string('lowest_rate');
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
    }
}
