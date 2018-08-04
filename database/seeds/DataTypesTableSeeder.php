<?php

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2018-08-04 01:18:18',
                'updated_at' => '2018-08-04 01:18:18',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2018-08-04 01:18:18',
                'updated_at' => '2018-08-04 01:18:18',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'departments',
                'slug' => 'departments',
                'display_name_singular' => 'Department',
                'display_name_plural' => 'Departments',
                'icon' => '',
                'model_name' => 'App\\Department',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 01:47:24',
                'updated_at' => '2018-08-04 03:00:10',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'App\\User',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:16:17',
            ),
            4 => 
            array (
                'id' => 7,
                'name' => 'lines',
                'slug' => 'lines',
                'display_name_singular' => 'Line',
                'display_name_plural' => 'Lines',
                'icon' => '',
                'model_name' => 'App\\Line',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 03:02:56',
            ),
            5 => 
            array (
                'id' => 8,
                'name' => 'scanners',
                'slug' => 'scanners',
                'display_name_singular' => 'Scanner',
                'display_name_plural' => 'Scanners',
                'icon' => '',
                'model_name' => 'App\\Scanner',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:57:25',
            ),
            6 => 
            array (
                'id' => 9,
                'name' => 'endpoints',
                'slug' => 'endpoints',
                'display_name_singular' => 'Endpoint',
                'display_name_plural' => 'Endpoints',
                'icon' => '',
                'model_name' => 'App\\Endpoint',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:18:22',
            ),
            7 => 
            array (
                'id' => 10,
                'name' => 'lineprocesses',
                'slug' => 'lineprocesses',
                'display_name_singular' => 'Lineprocess',
                'display_name_plural' => 'Lineprocesses',
                'icon' => '',
                'model_name' => 'App\\Lineprocess',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:32:25',
            ),
            8 => 
            array (
                'id' => 11,
                'name' => 'linetypes',
                'slug' => 'linetypes',
                'display_name_singular' => 'Linetype',
                'display_name_plural' => 'Linetypes',
                'icon' => '',
                'model_name' => 'App\\Linetype',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 03:34:15',
                'updated_at' => '2018-08-04 03:35:32',
            ),
            9 => 
            array (
                'id' => 12,
                'name' => 'sequences',
                'slug' => 'sequences',
                'display_name_singular' => 'Sequence',
                'display_name_plural' => 'Sequences',
                'icon' => '',
                'model_name' => 'App\\Sequence',
                'policy_name' => '',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":"","order_display_column":""}',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:40:29',
            ),
        ));
        
        
    }
}