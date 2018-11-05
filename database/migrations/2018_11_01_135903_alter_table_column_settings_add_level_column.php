<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableColumnSettingsAddLevelColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('column_settings', function (Blueprint $table) {
            if( !Schema::hasColumn('column_settings', 'level') ){
                $table->integer('level')->default(3); //selevel dengan board
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
        Schema::table('column_settings', function (Blueprint $table) {
            if( Schema::hasColumn('column_settings', 'level')){
                $table->dropColumn('level');
            }
        });
    }
}
