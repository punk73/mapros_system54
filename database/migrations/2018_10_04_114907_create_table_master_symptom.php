<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMasterSymptom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_symptom', function (Blueprint $table) {
            // $table->increments('id');
            $table->unsignedInteger('master_id');
            $table->unsignedInteger('symptom_id');
            $table->timestamps();

            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade');
            $table->foreign('symptom_id')->references('id')->on('symptoms')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_symptom');
    }
}
