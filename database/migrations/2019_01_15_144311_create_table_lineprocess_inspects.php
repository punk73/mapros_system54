<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLineprocessInspects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineprocess_inspects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lineprocess_id');
            $table->integer('has_log'); //it should be 0 or 1; boolean
            $table->foreign('lineprocess_id')
                ->reference('id')
                ->on('lineprocesses')
                ->onDelete('cascade');

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
        Schema::table('lineprocess_inspects', function (Blueprint $table) {
            if( Schema::hasColumn('lineprocess_inspects', 'lineprocess_id') ){
                // drop foreign key
                $table->dropForeign(['lineprocess_id']);
            }
        });

        Schema::dropIfExists('lineprocess_inspects');
    }
}
