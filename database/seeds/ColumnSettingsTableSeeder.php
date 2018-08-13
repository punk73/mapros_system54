<?php

use Illuminate\Database\Seeder;

class ColumnSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('column_settings')->delete();
        
        \DB::table('column_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'board',
                'dummy_column' => 'board_id',
                'table_name' => 'boards',
                'code_prefix' => NULL,
                'created_at' => '2018-08-13 13:57:40',
                'updated_at' => '2018-08-13 13:57:40',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'panel',
                'dummy_column' => 'ticket_no',
                'table_name' => 'tickets',
                'code_prefix' => '__PNL',
                'created_at' => '2018-08-13 13:58:04',
                'updated_at' => '2018-08-13 13:58:04',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'mecha',
                'dummy_column' => 'ticket_no',
                'table_name' => 'tickets',
                'code_prefix' => '__MCH',
                'created_at' => '2018-08-13 13:58:22',
                'updated_at' => '2018-08-13 13:58:22',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'master',
                'dummy_column' => 'ticket_no_master',
                'table_name' => 'masters',
                'code_prefix' => '__MST',
                'created_at' => '2018-08-13 13:58:46',
                'updated_at' => '2018-08-13 13:58:46',
            ),
        ));
        
        
    }
}