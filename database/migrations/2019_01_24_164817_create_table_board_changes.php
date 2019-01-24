<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBoardChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('board_id', 50);
            $table->string('unique_id', 120 );
            $table->string('board_id_new', 50);
            $table->string('nik', 30);
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
        Schema::dropIfExists('board_changes');
    }
}
