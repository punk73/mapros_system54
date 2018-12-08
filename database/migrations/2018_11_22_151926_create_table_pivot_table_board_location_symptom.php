<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePivotTableBoardLocationSymptom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_location_symptom', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_location_id');
            $table->unsignedInteger('symptom_id');

            $table->foreign('board_location_id')->references('id')->on('board_location')->onDelete('cascade');
            $table->foreign('symptom_id')->references('id')->on('symptoms')->onDelete('cascade');
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
        Schema::dropIfExists('board_location_symptom');
    }
}
