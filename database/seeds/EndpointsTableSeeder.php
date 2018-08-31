<?php

use Illuminate\Database\Seeder;

class EndpointsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('endpoints')->delete();
        
        \DB::table('endpoints')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'AOI',
                'url' => 'http://136.198.117.48/mapros_system54/public/api/aoies',
                'created_at' => '2018-08-28 12:31:00',
                'updated_at' => '2018-08-28 16:58:18',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'mecha inspection',
                'url' => 'http://136.198.117.48/mecha/api/inspects',
                'created_at' => '2018-08-29 11:53:00',
                'updated_at' => '2018-08-29 11:54:34',
            ),
        ));
        
        
    }
}