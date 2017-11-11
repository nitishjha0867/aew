<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_contact', function (Blueprint $table) {
            //
            $table->increments('contact_id');
            $table->integer('plant_id')->unsigned()->index();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->string('person_name');
            $table->string('person_designation');
            $table->string('person_phone');
            $table->string('person_mobile');
            $table->string('person_email');
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
        Schema::dropIfExists('clients_contact');
    }
}
