<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('departments')->delete();
        
        \DB::table('departments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'IT',
                'created_at' => '2018-08-05 11:05:00',
                'updated_at' => '2018-08-05 11:05:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'SMT',
                'created_at' => '2018-08-05 11:05:27',
                'updated_at' => '2018-08-05 11:05:27',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'MA',
                'created_at' => '2018-08-05 11:05:34',
                'updated_at' => '2018-08-05 11:05:34',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Planning',
                'created_at' => '2018-08-05 11:05:46',
                'updated_at' => '2018-08-05 11:05:46',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Purchasing',
                'created_at' => '2018-08-05 11:05:59',
                'updated_at' => '2018-08-05 11:05:59',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Production Engineering',
                'created_at' => '2018-08-05 11:06:13',
                'updated_at' => '2018-08-05 11:06:13',
            ),
        ));
        
        
    }
}