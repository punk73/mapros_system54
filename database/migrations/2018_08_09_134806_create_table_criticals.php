<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCriticals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criticals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_id');
            $table->integer('lineprocess_id');
            $table->string('unique_id');
            $table->string('supp_code');
            $table->string('part_no');
            $table->string('po');
            $table->string('production_date');
            $table->string('lotno');
            $table->integer('qty');
            $table->string('scan_nik');
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
        Schema::dropIfExists('criticals');
    }
}
