<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                //'id' => 2,
                'role_id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'avatar' => 'users/default.png',
                'nik' => '39597',
                'access_id' => NULL,
                'input_by' => NULL,
                'update_by' => NULL,
                'grade_id' => NULL,
                'department_id' => NULL,
                'password' => 'password',
                'remember_token' => 'Q5imdckLZemEafE300UVuIDyWKGwBVmg55ZsN3eJ4JO08dSHxTniQikjHhaO',
                'settings' => NULL,
                'created_at' => '2018-08-04 00:10:31',
                'updated_at' => '2018-08-04 03:47:35',
            ),
            1 => 
            array (
                //'id' => 3,
                'role_id' => 2,
                'name' => 'punk73',
                'email' => 'punk73@email.com',
                'avatar' => 'users/default.png',
                'nik' => '39598',
                'access_id' => NULL,
                'input_by' => NULL,
                'update_by' => NULL,
                'grade_id' => NULL,
                'department_id' => NULL,
                'password' => '123465',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2018-08-04 02:53:13',
                'updated_at' => '2018-08-04 02:53:13',
            ),
        ));
        
        
    }
}