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
                'id' => 42,
                'key' => 'browse_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            27 => 
            array (
                'id' => 43,
                'key' => 'read_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            28 => 
            array (
                'id' => 44,
                'key' => 'edit_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            29 => 
            array (
                'id' => 45,
                'key' => 'add_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            30 => 
            array (
                'id' => 46,
                'key' => 'delete_lines',
                'table_name' => 'lines',
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-04 02:29:14',
            ),
            31 => 
            array (
                'id' => 47,
                'key' => 'browse_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            32 => 
            array (
                'id' => 48,
                'key' => 'read_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            33 => 
            array (
                'id' => 49,
                'key' => 'edit_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            34 => 
            array (
                'id' => 50,
                'key' => 'add_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            35 => 
            array (
                'id' => 51,
                'key' => 'delete_scanners',
                'table_name' => 'scanners',
                'created_at' => '2018-08-04 02:38:11',
                'updated_at' => '2018-08-04 02:38:11',
            ),
            36 => 
            array (
                'id' => 57,
                'key' => 'browse_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            37 => 
            array (
                'id' => 58,
                'key' => 'read_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            38 => 
            array (
                'id' => 59,
                'key' => 'edit_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            39 => 
            array (
                'id' => 60,
                'key' => 'add_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            40 => 
            array (
                'id' => 61,
                'key' => 'delete_lineprocesses',
                'table_name' => 'lineprocesses',
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-04 03:31:01',
            ),
            41 => 
            array (
                'id' => 62,
                'key' => 'browse_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            42 => 
            array (
                'id' => 63,
                'key' => 'read_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            43 => 
            array (
                'id' => 64,
                'key' => 'edit_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            44 => 
            array (
                'id' => 65,
                'key' => 'add_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            45 => 
            array (
                'id' => 66,
                'key' => 'delete_linetypes',
                'table_name' => 'linetypes',
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-04 03:34:16',
            ),
            46 => 
            array (
                'id' => 67,
                'key' => 'browse_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            47 => 
            array (
                'id' => 68,
                'key' => 'read_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            48 => 
            array (
                'id' => 69,
                'key' => 'edit_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            49 => 
            array (
                'id' => 70,
                'key' => 'add_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            50 => 
            array (
                'id' => 71,
                'key' => 'delete_sequences',
                'table_name' => 'sequences',
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-04 03:38:49',
            ),
            51 => 
            array (
                'id' => 72,
                'key' => 'browse_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 07:39:35',
                'updated_at' => '2018-08-04 07:39:35',
            ),
            52 => 
            array (
                'id' => 73,
                'key' => 'read_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 07:39:35',
                'updated_at' => '2018-08-04 07:39:35',
            ),
            53 => 
            array (
                'id' => 74,
                'key' => 'edit_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 07:39:35',
                'updated_at' => '2018-08-04 07:39:35',
            ),
            54 => 
            array (
                'id' => 75,
                'key' => 'add_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 07:39:35',
                'updated_at' => '2018-08-04 07:39:35',
            ),
            55 => 
            array (
                'id' => 76,
                'key' => 'delete_endpoints',
                'table_name' => 'endpoints',
                'created_at' => '2018-08-04 07:39:35',
                'updated_at' => '2018-08-04 07:39:35',
            ),
            56 => 
            array (
                'id' => 82,
                'key' => 'browse_users',
                'table_name' => 'users',
                'created_at' => '2018-08-05 07:21:52',
                'updated_at' => '2018-08-05 07:21:52',
            ),
            57 => 
            array (
                'id' => 83,
                'key' => 'read_users',
                'table_name' => 'users',
                'created_at' => '2018-08-05 07:21:52',
                'updated_at' => '2018-08-05 07:21:52',
            ),
            58 => 
            array (
                'id' => 84,
                'key' => 'edit_users',
                'table_name' => 'users',
                'created_at' => '2018-08-05 07:21:52',
                'updated_at' => '2018-08-05 07:21:52',
            ),
            59 => 
            array (
                'id' => 85,
                'key' => 'add_users',
                'table_name' => 'users',
                'created_at' => '2018-08-05 07:21:52',
                'updated_at' => '2018-08-05 07:21:52',
            ),
            60 => 
            array (
                'id' => 86,
                'key' => 'delete_users',
                'table_name' => 'users',
                'created_at' => '2018-08-05 07:21:52',
                'updated_at' => '2018-08-05 07:21:52',
            ),
            61 => 
            array (
                'id' => 87,
                'key' => 'browse_grades',
                'table_name' => 'grades',
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-05 09:40:51',
            ),
            62 => 
            array (
                'id' => 88,
                'key' => 'read_grades',
                'table_name' => 'grades',
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-05 09:40:51',
            ),
            63 => 
            array (
                'id' => 89,
                'key' => 'edit_grades',
                'table_name' => 'grades',
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-05 09:40:51',
            ),
            64 => 
            array (
                'id' => 90,
                'key' => 'add_grades',
                'table_name' => 'grades',
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-05 09:40:51',
            ),
            65 => 
            array (
                'id' => 91,
                'key' => 'delete_grades',
                'table_name' => 'grades',
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-05 09:40:51',
            ),
            66 => 
            array (
                'id' => 92,
                'key' => 'browse_boards',
                'table_name' => 'boards',
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-08 09:50:17',
            ),
            67 => 
            array (
                'id' => 93,
                'key' => 'read_boards',
                'table_name' => 'boards',
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-08 09:50:17',
            ),
            68 => 
            array (
                'id' => 94,
                'key' => 'edit_boards',
                'table_name' => 'boards',
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-08 09:50:17',
            ),
            69 => 
            array (
                'id' => 95,
                'key' => 'add_boards',
                'table_name' => 'boards',
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-08 09:50:17',
            ),
            70 => 
            array (
                'id' => 96,
                'key' => 'delete_boards',
                'table_name' => 'boards',
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-08 09:50:17',
            ),
            71 => 
            array (
                'id' => 102,
                'key' => 'browse_column_settings',
                'table_name' => 'column_settings',
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-13 13:42:52',
            ),
            72 => 
            array (
                'id' => 103,
                'key' => 'read_column_settings',
                'table_name' => 'column_settings',
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-13 13:42:52',
            ),
            73 => 
            array (
                'id' => 104,
                'key' => 'edit_column_settings',
                'table_name' => 'column_settings',
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-13 13:42:52',
            ),
            74 => 
            array (
                'id' => 105,
                'key' => 'add_column_settings',
                'table_name' => 'column_settings',
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-13 13:42:52',
            ),
            75 => 
            array (
                'id' => 106,
                'key' => 'delete_column_settings',
                'table_name' => 'column_settings',
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-13 13:42:52',
            ),
            76 => 
            array (
                'id' => 107,
                'key' => 'browse_ticket_masters',
                'table_name' => 'ticket_masters',
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:22:11',
            ),
            77 => 
            array (
                'id' => 108,
                'key' => 'read_ticket_masters',
                'table_name' => 'ticket_masters',
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:22:11',
            ),
            78 => 
            array (
                'id' => 109,
                'key' => 'edit_ticket_masters',
                'table_name' => 'ticket_masters',
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:22:11',
            ),
            79 => 
            array (
                'id' => 110,
                'key' => 'add_ticket_masters',
                'table_name' => 'ticket_masters',
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:22:11',
            ),
            80 => 
            array (
                'id' => 111,
                'key' => 'delete_ticket_masters',
                'table_name' => 'ticket_masters',
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:22:11',
            ),
            81 => 
            array (
                'id' => 112,
                'key' => 'browse_tickets',
                'table_name' => 'tickets',
                'created_at' => '2018-08-24 09:53:38',
                'updated_at' => '2018-08-24 09:53:38',
            ),
            82 => 
            array (
                'id' => 113,
                'key' => 'read_tickets',
                'table_name' => 'tickets',
                'created_at' => '2018-08-24 09:53:38',
                'updated_at' => '2018-08-24 09:53:38',
            ),
            83 => 
            array (
                'id' => 114,
                'key' => 'edit_tickets',
                'table_name' => 'tickets',
                'created_at' => '2018-08-24 09:53:38',
                'updated_at' => '2018-08-24 09:53:38',
            ),
            84 => 
            array (
                'id' => 115,
                'key' => 'add_tickets',
                'table_name' => 'tickets',
                'created_at' => '2018-08-24 09:53:38',
                'updated_at' => '2018-08-24 09:53:38',
            ),
            85 => 
            array (
                'id' => 116,
                'key' => 'delete_tickets',
                'table_name' => 'tickets',
                'created_at' => '2018-08-24 09:53:38',
                'updated_at' => '2018-08-24 09:53:38',
            ),
            86 => 
            array (
                'id' => 117,
                'key' => 'browse_masters',
                'table_name' => 'masters',
                'created_at' => '2018-08-26 07:37:12',
                'updated_at' => '2018-08-26 07:37:12',
            ),
            87 => 
            array (
                'id' => 118,
                'key' => 'read_masters',
                'table_name' => 'masters',
                'created_at' => '2018-08-26 07:37:12',
                'updated_at' => '2018-08-26 07:37:12',
            ),
            88 => 
            array (
                'id' => 119,
                'key' => 'edit_masters',
                'table_name' => 'masters',
                'created_at' => '2018-08-26 07:37:12',
                'updated_at' => '2018-08-26 07:37:12',
            ),
            89 => 
            array (
                'id' => 120,
                'key' => 'add_masters',
                'table_name' => 'masters',
                'created_at' => '2018-08-26 07:37:12',
                'updated_at' => '2018-08-26 07:37:12',
            ),
            90 => 
            array (
                'id' => 121,
                'key' => 'delete_masters',
                'table_name' => 'masters',
                'created_at' => '2018-08-26 07:37:12',
                'updated_at' => '2018-08-26 07:37:12',
            ),
            91 => 
            array (
                'id' => 122,
                'key' => 'browse_criticals',
                'table_name' => 'criticals',
                'created_at' => '2018-08-30 14:39:25',
                'updated_at' => '2018-08-30 14:39:25',
            ),
            92 => 
            array (
                'id' => 123,
                'key' => 'read_criticals',
                'table_name' => 'criticals',
                'created_at' => '2018-08-30 14:39:25',
                'updated_at' => '2018-08-30 14:39:25',
            ),
            93 => 
            array (
                'id' => 124,
                'key' => 'edit_criticals',
                'table_name' => 'criticals',
                'created_at' => '2018-08-30 14:39:25',
                'updated_at' => '2018-08-30 14:39:25',
            ),
            94 => 
            array (
                'id' => 125,
                'key' => 'add_criticals',
                'table_name' => 'criticals',
                'created_at' => '2018-08-30 14:39:25',
                'updated_at' => '2018-08-30 14:39:25',
            ),
            95 => 
            array (
                'id' => 126,
                'key' => 'delete_criticals',
                'table_name' => 'criticals',
                'created_at' => '2018-08-30 14:39:25',
                'updated_at' => '2018-08-30 14:39:25',
            ),
            96 => 
            array (
                'id' => 127,
                'key' => 'browse_repairs',
                'table_name' => 'repairs',
                'created_at' => '2018-09-05 09:54:58',
                'updated_at' => '2018-09-05 09:54:58',
            ),
            97 => 
            array (
                'id' => 128,
                'key' => 'read_repairs',
                'table_name' => 'repairs',
                'created_at' => '2018-09-05 09:54:58',
                'updated_at' => '2018-09-05 09:54:58',
            ),
            98 => 
            array (
                'id' => 129,
                'key' => 'edit_repairs',
                'table_name' => 'repairs',
                'created_at' => '2018-09-05 09:54:58',
                'updated_at' => '2018-09-05 09:54:58',
            ),
            99 => 
            array (
                'id' => 130,
                'key' => 'add_repairs',
                'table_name' => 'repairs',
                'created_at' => '2018-09-05 09:54:58',
                'updated_at' => '2018-09-05 09:54:58',
            ),
            100 => 
            array (
                'id' => 131,
                'key' => 'delete_repairs',
                'table_name' => 'repairs',
                'created_at' => '2018-09-05 09:54:58',
                'updated_at' => '2018-09-05 09:54:58',
            ),
            101 => 
            array (
                'id' => 132,
                'key' => 'browse_symptoms',
                'table_name' => 'symptoms',
                'created_at' => '2018-10-04 10:19:29',
                'updated_at' => '2018-10-04 10:19:29',
            ),
            102 => 
            array (
                'id' => 133,
                'key' => 'read_symptoms',
                'table_name' => 'symptoms',
                'created_at' => '2018-10-04 10:19:29',
                'updated_at' => '2018-10-04 10:19:29',
            ),
            103 => 
            array (
                'id' => 134,
                'key' => 'edit_symptoms',
                'table_name' => 'symptoms',
                'created_at' => '2018-10-04 10:19:29',
                'updated_at' => '2018-10-04 10:19:29',
            ),
            104 => 
            array (
                'id' => 135,
                'key' => 'add_symptoms',
                'table_name' => 'symptoms',
                'created_at' => '2018-10-04 10:19:29',
                'updated_at' => '2018-10-04 10:19:29',
            ),
            105 => 
            array (
                'id' => 136,
                'key' => 'delete_symptoms',
                'table_name' => 'symptoms',
                'created_at' => '2018-10-04 10:19:29',
                'updated_at' => '2018-10-04 10:19:29',
            ),
        ));
        
        
    }
}