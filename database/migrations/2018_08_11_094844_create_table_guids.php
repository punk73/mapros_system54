<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGuids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->unique();
            $table->integer('scanner_id');
            $table->string('dummy_id');
            $table->string('board_id');
            $table->string('guid_master');

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
        Schema::dropIfExists('guids');
    }
}
