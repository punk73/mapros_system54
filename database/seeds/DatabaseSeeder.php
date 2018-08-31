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
        $this->call(SequenceSeeder::class);
        $this->call(LineprocessSeeder::class);
        $this->call(ScannerSeeder::class);
        $this->call(EndpointSeeder::class);
        $this->call(LineSeeder::class);
        $this->call(LinetypeSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(GradesTableSeeder::class);
        $this->call(ColumnSettingsTableSeeder::class);
        
    }
}
