<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLineprocessCartons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineprocess_cartons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lineprocess_id');
            $table->unsignedInteger('scanner_id');
            $table->string('modelname', 60); //this for model 
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
        Schema::table('lineprocess_cartons', function (Blueprint $table) {
            if( Schema::hasColumn('lineprocess_cartons', 'lineprocess_id') ){
                // drop foreign key
                $table->dropForeign(['lineprocess_id']);
            }

            if( Schema::hasColumn('lineprocess_cartons', 'scanner_id') ){
                // drop foreign key
                $table->dropForeign(['scanner_id']);
            }
        });

        Schema::dropIfExists('lineprocess_cartons');
    }
}
