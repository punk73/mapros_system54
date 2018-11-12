<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePivorCriticalNode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critical_node', function (Blueprint $table){
            $table->unsignedInteger('critical_id'); //foreign of critical id
            $table->string('unique_id'); //contain board_id, guid_master or guid_ticket;
            $table->timestamps();

            $table->foreign('critical_id')->references('id')->on('criticals')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critical_node');
    }
}
