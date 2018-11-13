<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLineprocessStarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineprocess_starts', function (Blueprint $table) {
            $table->bigInteger('lineprocess_id');
            $table->bigInteger('start_id'); // refer to lineprocess_id that become starting points;
            $table->timestamps();

            $table->primary('lineprocess_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lineprocess_starts');
    }
}
