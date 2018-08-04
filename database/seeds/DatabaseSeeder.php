<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ScannerSeeder::class);
        $this->call(SequenceSeeder::class);
        $this->call(LineprocessSeeder::class);
        $this->call(EndpointSeeder::class);
        $this->call(LineSeeder::class);
        $this->call(LinetypeSeeder::class);
        $this->call(VoyagerDatabaseSeeder::class);
        // user seeder should be in the last
        $this->call(UsersTableSeeder::class);
        // $this->call(DataRowsTableSeeder::class);
        // $this->call(DataTypesTableSeeder::class);
        // $this->call(MenuItemsTableSeeder::class);
        // $this->call(SettingsTableSeeder::class);
        // $this->call(PermissionsTableSeeder::class);
        // $this->call(PermissionRoleTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
