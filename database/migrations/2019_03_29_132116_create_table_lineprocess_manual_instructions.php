<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLineprocessManualInstructions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineprocess_manual_instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lineprocess_id');
            $table->unsignedInteger('scanner_id');

            $table->integer('has_check'); //it should be 0 or 1; boolean
            $table->foreign('lineprocess_id')
                ->references('id')
                ->on('lineprocesses')
                ->onDelete('cascade');
            
            $table->foreign('scanner_id')
                ->references('id')
                ->on('scanners')
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
        Schema::table('lineprocess_manual_instructions', function (Blueprint $table) {
            if( Schema::hasColumn('lineprocess_manual_instructions', 'lineprocess_id') ){
                // drop foreign key
                $table->dropForeign(['lineprocess_id']);
            }

            if( Schema::hasColumn('lineprocess_manual_instructions', 'scanner_id') ){
                // drop foreign key
                $table->dropForeign(['scanner_id']);
            }
        });

        Schema::dropIfExists('lineprocess_manual_instructions');
    }
}
