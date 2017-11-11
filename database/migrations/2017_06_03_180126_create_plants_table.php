<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            //
            $table->increments('plant_id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('client_id')->on('clients');
            $table->string('plant_name');
            $table->string('plant_address', 255);
            $table->string('plant_state')->index();
            $table->string('plant_city')->index();
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
        Schema::dropIfExists('plants');
    }
}
