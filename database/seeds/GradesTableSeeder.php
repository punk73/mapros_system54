<?php

use Illuminate\Database\Seeder;

class GradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('grades')->delete();
        
        \DB::table('grades')->insert(array (
            0 => 
            array (
                //'id' => 1,
                'name' => 'Leader 2',
                'created_at' => '2018-08-05 11:08:55',
                'updated_at' => '2018-08-05 11:08:55',
            ),
            1 => 
            array (
                //'id' => 2,
                'name' => 'Leader 1',
                'created_at' => '2018-08-05 11:09:03',
                'updated_at' => '2018-08-05 11:09:03',
            ),
            2 => 
            array (
                //'id' => 3,
                'name' => 'Assistant Supervisor',
                'created_at' => '2018-08-05 11:09:15',
                'updated_at' => '2018-08-05 11:09:15',
            ),
            3 => 
            array (
                //'id' => 4,
                'name' => 'Supervisor',
                'created_at' => '2018-08-05 11:09:24',
                'updated_at' => '2018-08-05 11:09:24',
            ),
            4 => 
            array (
                //'id' => 5,
                'name' => 'Assistant Manager',
                'created_at' => '2018-08-05 11:09:33',
                'updated_at' => '2018-08-05 11:09:33',
            ),
            5 => 
            array (
                //'id' => 6,
                'name' => 'Manager',
                'created_at' => '2018-08-05 11:09:46',
                'updated_at' => '2018-08-05 11:09:46',
            ),
        ));
        
        
    }
}