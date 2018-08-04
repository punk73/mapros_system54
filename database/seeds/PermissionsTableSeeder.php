<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'browse_admin',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'browse_bread',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'browse_database',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'browse_media',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'browse_compass',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'browse_menus',
                'table_name' => 'menus',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'read_menus',
                'table_name' => 'menus',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'edit_menus',
                'table_name' => 'menus',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'add_menus',
                'table_name' => 'menus',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'delete_menus',
                'table_name' => 'menus',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            10 => 
            array (
                'id' => 11,
                'key' => 'browse_roles',
                'table_name' => 'roles',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            11 => 
            array (
                'id' => 12,
                'key' => 'read_roles',
                'table_name' => 'roles',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            12 => 
            array (
                'id' => 13,
                'key' => 'edit_roles',
                'table_name' => 'roles',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            13 => 
            array (
                'id' => 14,
                'key' => 'add_roles',
                'table_name' => 'roles',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            14 => 
            array (
                'id' => 15,
                'key' => 'delete_roles',
                'table_name' => 'roles',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            15 => 
            array (
                'id' => 21,
                'key' => 'browse_settings',
                'table_name' => 'settings',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            16 => 
            array (
                'id' => 22,
                'key' => 'read_settings',
                'table_name' => 'settings',
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
            ),
            17 => 
            array (
                'id' => 23,
                'key' => 'edit_settings',
                'table_name' => 'settings',
                'created_at' => '2018-08-04 01:18:21',
                'updated_at' => '2018-08-04 01:18:21',
            ),
            18 => 
            array (
                'id' => 24,
                'key' => 'add_settings',
                'table_name' => 'settings',
                'created_at' => '2018-08-04 01:18:21',
                'updated_at' => '2018-08-04 01:18:21',
            ),
            19 => 
            array (
                'id' => 25,
                'key' => 'delete_settings',
                'table_name' => 'settings',
                'created_at' => '2018-08-04 01:18:21',
                'updated_at' => '2018-08-04 01:18:21',
            ),
            20 => 
            array (
                'id' => 26,
                'key' => 'browse_hooks',
                'table_name' => NULL,
                'created_at' => '2018-08-04 01:18:21',
                'updated_at' => '2018-08-04 01:18:21',
            ),
            21 => 
            array (
                'id' => 27,
                'key' => 'browse_departments',
                'table_name' => 'departments',
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-04 01:47:25',
            ),
            22 => 
            array (
                'id' => 28,
                'key' => 'read_departments',
                'table_name' => 'departments',
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-04 01:47:25',
            ),
            23 => 
            array (
                'id' => 29,
                'key' => 'edit_departments',
                'table_name' => 'departments',
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-04 01:47:25',
            ),
            24 => 
            array (
                'id' => 30,
                'key' => 'add_departments',
                'table_name' => 'departments',
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-04 01:47:25',
            ),
            25 => 
            array (
                'id' => 31,
                'key' => 'delete_departments',
                'table_name' => 'departments',
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-04 01:47:25',
            ),
            26 => 
            array (
                'id' => 32,
                'key' => 'browse_users',
                'table_name' => 'users',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:11:11',
            ),
            27 => 
            array (
                'id' => 33,
                'key' => 'read_users',
                'table_name' => 'users',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:11:11',
            ),
            28 => 
            array (
                'id' => 34,
                'key' => 'edit_users',
                'table_name' => 'users',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:11:11',
            ),
            29 => 
            array (
                'id' => 35,
                'key' => 'add_users',
                'table_name' => 'users',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:11:11',
            ),
            30 => 
            array (
                'id' => 36,
                'key' => 'delete_users',
                'table_name' => 'users',
                'created_at' => '2018-08-04 02:11:11',
                'updated_at' => '2018-08-04 02:11:11',
            ),
            31 => 
            array (
                'id' => 42,
                'key' => 'browse_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            32 => 
            array (
                'id' => 43,
                'key' => 'read_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            33 => 
            array (
                'id' => 44,
                'key' => 'edit_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            34 => 
            array (
                'id' => 45,
                'key' => 'add_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            35 => 
            array (
                'id' => 46,
                'key' => 'delete_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            36 => 
            array (
                'id' => 47,
                'key' => 'browse_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            37 => 
            array (
                'id' => 48,
                'key' => 'read_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            38 => 
            array (
                'id' => 49,
                'key' => 'edit_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            39 => 
            array (
                'id' => 50,
                'key' => 'add_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            40 => 
            array (
                'id' => 51,
                'key' => 'delete_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            41 => 
            array (
                'id' => 52,
                'key' => 'browse_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:17:02',
            ),
            42 => 
            array (
                'id' => 53,
                'key' => 'read_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:17:02',
            ),
            43 => 
            array (
                'id' => 54,
                'key' => 'edit_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:17:02',
            ),
            44 => 
            array (
                'id' => 55,
                'key' => 'add_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:17:02',
            ),
            45 => 
            array (
                'id' => 56,
                'key' => 'delete_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-04 03:17:02',
            ),
            46 => 
            array (
                'id' => 57,
                'key' => 'browse_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            47 => 
            array (
                'id' => 58,
                'key' => 'read_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            48 => 
            array (
                'id' => 59,
                'key' => 'edit_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            49 => 
            array (
                'id' => 60,
                'key' => 'add_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            50 => 
            array (
                'id' => 61,
                'key' => 'delete_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            51 => 
            array (
                'id' => 62,
                'key' => 'browse_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            52 => 
            array (
                'id' => 63,
                'key' => 'read_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            53 => 
            array (
                'id' => 64,
                'key' => 'edit_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            54 => 
            array (
                'id' => 65,
                'key' => 'add_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            55 => 
            array (
                'id' => 66,
                'key' => 'delete_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            56 => 
            array (
                'id' => 67,
                'key' => 'browse_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            57 => 
            array (
                'id' => 68,
                'key' => 'read_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            58 => 
            array (
                'id' => 69,
                'key' => 'edit_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            59 => 
            array (
                'id' => 70,
                'key' => 'add_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            60 => 
            array (
                'id' => 71,
                'key' => 'delete_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
        ));
        
        
    }
}