<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSymptomCodeBecomeUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symptoms', function (Blueprint $table) {
            //set code become unique
            if( Schema::hasColumn('symptoms', 'code') ){
                $table->unique('code');
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
        Schema::table('symptoms', function (Blueprint $table) {
            if( Schema::hasColumn('symptoms', 'code') ){
                $table->dropUnique('code');
            }
        });
    }
}
