<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePartsTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode');
            $table->string('guid_master')->nullable();
            $table->string('guid_ticket')->nullable();
            $table->string('modelname')->nullable();
            $table->string('lotno')->nullable();
            $table->integer('scanner_id');
            $table->string('status');
            $table->string('scan_nik');
            $table->string('judge')->default('OK');
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
        Schema::dropIfExists('parts');
    }
}
