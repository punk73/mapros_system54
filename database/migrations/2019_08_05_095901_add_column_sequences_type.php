<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSequencesType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('sequences')) {
            Schema::table('sequences', function (Blueprint $table) {
                //ini untuk membedakan model SKD atau bukan.
                if( Schema::hasColumn('sequences', 'type') == false ){
                    $table->string('type', 100)->default("normal")->nullable();
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
        if (Schema::hasTable('sequences')) {
            Schema::table('sequences', function (Blueprint $table) {
                if(Schema::hasColumn('sequences', 'type') ){
                    $table->dropColumn('type');
                }
            });
        }
    }
}
