<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePwbs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pwbs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('model_header_id'); //->unsigned();
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('model_header_id')->references('id')->on('model_headers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pwbs');
    }
}
