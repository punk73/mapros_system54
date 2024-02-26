<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogQrPanels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_qr_panels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid_master', 120)->nullable();
            $table->string('guid_ticket', 120)->nullable();
            $table->string('content', 100);
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
        //
        Schema::dropIfExists('log_qr_panels');
    }
}
