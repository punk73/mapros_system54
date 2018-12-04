<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRepairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repairs', function (Blueprint $table) {
            if( !Schema::hasColumn('repairs', 'ng_lineprocess_id') ){
                $table->unsignedInteger('ng_lineprocess_id')->nullable(); //selevel dengan board
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
        Schema::table('repairs', function (Blueprint $table) {
            if( Schema::hasColumn('repairs', 'ng_lineprocess_id')){
                $table->dropColumn('ng_lineprocess_id');
            }
        });
    }
}
