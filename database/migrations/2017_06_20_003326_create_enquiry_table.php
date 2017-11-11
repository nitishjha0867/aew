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
		$table->string('quotation_file_name')->default('');
		$table->integer('quotation_revisions')->default(0);
		$table->integer('client_id')->unsigned()->index();
		$table->foreign('client_id')->references('client_id')->on('clients');
		$table->integer('plant_id')->unsigned()->index();
		$table->foreign('plant_id')->references('plant_id')->on('plants');
		$table->string('enquiry_no')->index();
		$table->integer('attachment_id')->unsigned()->index();
		$table->foreign('attachment_id')->references('attachment_id')->on('attachments');
		$table->string('product_id');
		// $table->foreign('product_id')->references('product_id')->on('products'); // cannot establish foreign key relationship since we'll save multiple entries in one cell
		$table->date('enquiry_date')->index();
		$table->date('due_date')->index();
		$table->boolean('enquiry_submitted')->default(0);
		$table->string('total_order_value');
		$table->string('negotitated_rate')->default('');
		$table->integer('contact_person')->unsigned()->index();
		$table->foreign('contact_person')->references('contact_id')->on('clients_contact');
		// $table->foreign('contact_person')->references('person_name')->on('clients_contact'); // creating problem maybe because contact_person i.e. person_name in clients_contact is not unique
		$table->string('lowest_rate');
		$table->string('products_note')->default('');
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
        Schema::dropIfExists('enquiry');
    }
}
