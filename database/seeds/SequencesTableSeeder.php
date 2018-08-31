<?php

use Illuminate\Database\Seeder;

class SequencesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sequences')->delete();
        
        \DB::table('sequences')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'NMZK-W69DJN',
                'modelname' => 'NMZK-W69DJN',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-28 16:59:28',
                'updated_at' => '2018-08-29 13:25:51',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'DMX8018SXN',
                'modelname' => 'DMX8018SXN',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:12:08',
                'updated_at' => '2018-08-30 12:12:08',
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'DPXGT700RA9N',
                'modelname' => 'DPXGT700RA9N',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:15:41',
                'updated_at' => '2018-08-30 12:15:41',
            ),
            3 => 
            array (
                'id' => 6,
                'name' => 'DPXGT701RA9N',
                'modelname' => 'DPXGT701RA9N',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:18:28',
                'updated_at' => '2018-08-30 12:18:28',
            ),
            4 => 
            array (
                'id' => 7,
                'name' => 'DPXGT702LA9N',
                'modelname' => 'DPXGT702LA9N',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:18:57',
                'updated_at' => '2018-08-30 12:19:35',
            ),
            5 => 
            array (
                'id' => 8,
                'name' => 'DPXGT502LA9N',
                'modelname' => 'DPXGT502LA9N',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:20:11',
                'updated_at' => '2018-08-30 12:20:11',
            ),
            6 => 
            array (
                'id' => 9,
                'name' => 'DPXGT500RA9N',
                'modelname' => 'DPXGT500RA9N',
                'pwbname' => 'MAIN',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 12:20:53',
                'updated_at' => '2018-08-30 12:20:53',
            ),
            7 => 
            array (
                'id' => 10,
                'name' => 'DMX8018SXN',
                'modelname' => 'DMX8018SXN',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:27:13',
                'updated_at' => '2018-08-30 13:27:13',
            ),
            8 => 
            array (
                'id' => 11,
                'name' => 'DPXGT700RA9N',
                'modelname' => 'DPXGT700RA9N',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:27:54',
                'updated_at' => '2018-08-30 13:27:54',
            ),
            9 => 
            array (
                'id' => 12,
                'name' => 'DPXGT701RA9N',
                'modelname' => 'DPXGT701RA9N',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:30:59',
                'updated_at' => '2018-08-30 13:30:59',
            ),
            10 => 
            array (
                'id' => 13,
                'name' => 'DPXGT702LA9N',
                'modelname' => 'DPXGT702LA9N',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:31:59',
                'updated_at' => '2018-08-30 13:31:59',
            ),
            11 => 
            array (
                'id' => 14,
                'name' => 'DPXGT502LA9N',
                'modelname' => 'DPXGT502LA9N',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:32:42',
                'updated_at' => '2018-08-30 13:32:42',
            ),
            12 => 
            array (
                'id' => 15,
                'name' => 'DPXGT500RA9N',
                'modelname' => 'DPXGT500RA9N',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:33:03',
                'updated_at' => '2018-08-30 13:33:03',
            ),
            13 => 
            array (
                'id' => 16,
                'name' => 'NMZK-W69DJN',
                'modelname' => 'NMZK-W69DJN',
                'pwbname' => 'master',
                'line_id' => 1,
                'process' => '1,3,4,9,10,11,12,18',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-30 13:40:24',
                'updated_at' => '2018-08-30 13:40:24',
            ),
            14 => 
            array (
                'id' => 17,
                'name' => 'DPXGT500RA9N',
                'modelname' => 'DPXGT500RA9N',
                'pwbname' => 'SWITCH',
                'line_id' => 1,
                'process' => '1,14',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-31 13:48:04',
                'updated_at' => '2018-08-31 13:48:04',
            ),
            15 => 
            array (
                'id' => 18,
                'name' => 'DPXGT502LA9N',
                'modelname' => 'DPXGT502LA9N',
                'pwbname' => 'SWITCH',
                'line_id' => 1,
                'process' => '1,14',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-31 13:48:40',
                'updated_at' => '2018-08-31 13:48:40',
            ),
            16 => 
            array (
                'id' => 19,
                'name' => 'DPXGT702LA9N',
                'modelname' => 'DPXGT702LA9N',
                'pwbname' => 'SWITCH',
                'line_id' => 1,
                'process' => '1,14',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-31 13:49:17',
                'updated_at' => '2018-08-31 13:49:17',
            ),
            17 => 
            array (
                'id' => 20,
                'name' => 'DPXGT701RA9N',
                'modelname' => 'DPXGT701RA9N',
                'pwbname' => 'SWITCH',
                'line_id' => 1,
                'process' => '1,14',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-31 13:50:02',
                'updated_at' => '2018-08-31 13:50:02',
            ),
            18 => 
            array (
                'id' => 21,
                'name' => 'DPXGT700RA9N',
                'modelname' => 'DPXGT700RA9N',
                'pwbname' => 'SWITCH',
                'line_id' => 1,
                'process' => '1,14',
                'update_by' => NULL,
                'input_by' => NULL,
                'created_at' => '2018-08-31 13:50:38',
                'updated_at' => '2018-08-31 13:50:38',
            ),
        ));
        
        
    }
}