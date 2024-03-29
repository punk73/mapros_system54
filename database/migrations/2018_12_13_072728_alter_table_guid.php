<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableGuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guids', function (Blueprint $table) {
            //set code become unique
            if( Schema::hasColumn('guids', 'scanner_id') ){
                $table->dropColumn('scanner_id');
            }
        });

        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'board_id') ){
                $table->dropColumn('board_id');
            }
        });

        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'guid_master') ){
                $table->dropColumn('guid_master');
            }
        });

        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'dummy_id') ){
                $table->string('dummy_id', 50 )->nullable()->change();
            }
        });
        
        Schema::table('guids', function (Blueprint $table) {
            if(Schema::hasColumn('guids', 'serial_no') == false ){
                $table->string('serial_no', 50)->nullable();
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'scanner_id') === false ){
                $table->integer('scanner_id')->nullable();
            }
        });
        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'board_id') === false ){
                $table->string('board_id')->nullable();
            }
        });
        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'guid_master') === false ){
                $table->string('guid_master')->nullable();
            }
        });
        Schema::table('guids', function (Blueprint $table) {
            if( Schema::hasColumn('guids', 'dummy_id') ){
                $table->string('dummy_id', 50 )->change();
            }
        });
        Schema::table('guids', function (Blueprint $table) {
            if(Schema::hasColumn('guids', 'serial_no')){
                $table->dropColumn('serial_no');
            }
        });
    }
}
