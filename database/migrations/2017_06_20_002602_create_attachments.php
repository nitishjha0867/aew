<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('attachments', function (Blueprint $table) {
			$table->increments('attachment_id');
			$table->longText('client_drawing_path');
			$table->string('client_drwaing_no')->index();
			$table->string('cost_sheet_path')->default('');
			$table->string('aew_drawing_path')->default('');
			$table->string('enquiry_mail_path')->default('');
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
        Schema::dropIfExists('attachments');
    }
}
