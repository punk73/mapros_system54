<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInspectionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id');
            $table->string('dummy_id')->nullable();
            $table->unsignedInteger('lineprocess_id')->nullable(); //lineprocess_id
            $table->unsignedInteger('scanner_id')->nullable(); //why ?? biar bisa milih
            $table->string('judgement', 30 ); //OK or NG         
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
        Schema::dropIfExists('inspection_logs');
    }
}
