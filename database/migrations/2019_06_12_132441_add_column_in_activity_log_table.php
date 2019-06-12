<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     * Add column untuk activity logs
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('activity_log')) {
            Schema::table('activity_log', function (Blueprint $table) {
                //set code become unique
                if( Schema::hasColumn('activity_log', 'value') == false ){
                    $table->text('value')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasTable('activity_log')) {
            Schema::table('activity_log', function (Blueprint $table) {
                if(Schema::hasColumn('activity_log', 'value') ){
                    $table->dropColumn('value');
                }
            });
        }
    }
}
