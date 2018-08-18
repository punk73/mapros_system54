<?php

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Dashboard',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-boat',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2018-08-04 01:18:19',
                'updated_at' => '2018-08-04 01:18:19',
                'route' => 'voyager.dashboard',
                'parameters' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Users',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
                'route' => 'voyager.users.index',
                'parameters' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-04 01:18:20',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Tools',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => NULL,
                'parameters' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Settings',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2018-08-04 01:18:20',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.settings.index',
                'parameters' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Hooks',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-hook',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2018-08-04 01:18:21',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.hooks',
                'parameters' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'title' => 'Departments',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2018-08-04 01:47:25',
                'updated_at' => '2018-08-14 12:33:00',
                'route' => 'voyager.departments.index',
                'parameters' => 'null',
            ),
            12 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'title' => 'Scanners',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2018-08-04 02:25:42',
                'updated_at' => '2018-08-14 12:34:56',
                'route' => 'voyager.scanners.index',
                'parameters' => 'null',
            ),
            13 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'title' => 'Lines',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2018-08-04 02:29:14',
                'updated_at' => '2018-08-14 12:29:44',
                'route' => 'voyager.lines.index',
                'parameters' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Endpoints',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 12,
                'created_at' => '2018-08-04 03:17:02',
                'updated_at' => '2018-08-14 12:31:47',
                'route' => 'voyager.endpoints.index',
                'parameters' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'menu_id' => 1,
                'title' => 'Lineprocesses',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 11,
                'created_at' => '2018-08-04 03:31:01',
                'updated_at' => '2018-08-14 12:31:47',
                'route' => 'voyager.lineprocesses.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'menu_id' => 1,
                'title' => 'Linetypes',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 10,
                'created_at' => '2018-08-04 03:34:16',
                'updated_at' => '2018-08-14 12:31:45',
                'route' => 'voyager.linetypes.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'menu_id' => 1,
                'title' => 'Sequences',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 13,
                'created_at' => '2018-08-04 03:38:49',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.sequences.index',
                'parameters' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'menu_id' => 1,
                'title' => 'Activity Logs',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 14,
                'created_at' => '2018-08-04 23:49:00',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.activity-log.index',
                'parameters' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'menu_id' => 1,
                'title' => 'Grades',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 15,
                'created_at' => '2018-08-05 09:40:51',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.grades.index',
                'parameters' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'menu_id' => 1,
                'title' => 'Boards',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 16,
                'created_at' => '2018-08-08 09:50:17',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.boards.index',
                'parameters' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'menu_id' => 1,
                'title' => 'Master Criticals',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 17,
                'created_at' => '2018-08-09 13:55:03',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.master-criticals.index',
                'parameters' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'menu_id' => 1,
                'title' => 'Column Settings',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 18,
                'created_at' => '2018-08-13 13:42:52',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.column-settings.index',
                'parameters' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'menu_id' => 1,
                'title' => 'Ticket Masters',
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 19,
                'created_at' => '2018-08-14 12:22:11',
                'updated_at' => '2018-08-14 12:31:43',
                'route' => 'voyager.ticket-masters.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}