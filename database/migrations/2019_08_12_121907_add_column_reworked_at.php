<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReworkedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->add_column('masters', 'reworked_at');
        $this->add_column('tickets', 'reworked_at');
        $this->add_column('boards', 'reworked_at');
        $this->add_column('parts', 'reworked_at');

    }

    protected function add_column($tableName, $columnName) {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columnName) {
                //ini untuk membedakan model SKD atau bukan.
                if( Schema::hasColumn($tableName, $columnName) == false ){
                    $table->timestamp($columnName)->nullable();
                }
            });
        }
    }

    protected function delete_column($tableName, $columnName) {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columnName) {
                if(Schema::hasColumn($tableName, $columnName) ){
                    $table->dropColumn($columnName);
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
        $this->delete_column('masters', 'reworked_at');
        $this->delete_column('tickets', 'reworked_at');
        $this->delete_column('boards', 'reworked_at');
        $this->delete_column('parts', 'reworked_at');
    }
}
