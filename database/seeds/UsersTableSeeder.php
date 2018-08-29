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
                'id' => 1,
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
                'password' => '$2y$10$Se9iAIaaI2Nay5z6N0Q8seOohWHLrPz/QtY3/TSHOYgXiNRbCdrWi',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2018-08-24 17:27:34',
                'updated_at' => '2018-08-24 17:27:34',
            ),
        ));
        
        
    }
}