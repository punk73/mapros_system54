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
        // $this->call(SequenceSeeder::class);
        $this->call(SequencesTableSeeder::class);
        $this->call(LineprocessSeeder::class);
        $this->call(ScannerSeeder::class);
        $this->call(LineSeeder::class);
        $this->call(LinetypeSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(GradesTableSeeder::class);
        $this->call(ColumnSettingsTableSeeder::class);
        $this->call(EndpointsTableSeeder::class);
        $this->call(DataTypesTableSeeder::class);
        $this->call(DataRowsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuItemsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }
}
