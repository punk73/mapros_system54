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
        Schema::create('critial_node', function (Blueprint $table){
            $table->unsignedInteger('critical_id'); //foreign of critical id
            $table->string('unique_id'); //contain board_id, guid_master or guid_ticket;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critial_node');
    }
}
