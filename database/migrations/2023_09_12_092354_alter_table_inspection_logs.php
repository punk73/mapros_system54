<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableInspectionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasTable('inspection_logs')) {

            Schema::table('inspection_logs',function(Blueprint $table) {
                if (Schema::hasColumn('inspection_logs', 'qr_panel') == false) {
                    $table->text('qr_panel')->nullable();
                }
                if (Schema::hasColumn('inspection_logs', 'sirius') == false) {
                    $table->text('sirius')->nullable();
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
        if (Schema::hasTable('inspection_logs')) {
            Schema::table('inspection_logs', function (Blueprint $table) {
                if (Schema::hasColumn('inspection_logs', 'qr_panel')) {
                    $table->dropColumn('qr_panel');
                }
                if (Schema::hasColumn('inspection_logs', 'sirius')) {
                    $table->dropColumn('sirius');
                }
            });
        }
    }
}
