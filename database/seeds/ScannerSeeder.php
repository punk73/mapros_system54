<?php

use Illuminate\Database\Seeder;
use App\Scanner;
use App\Lineprocess;

class ScannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Scanner::class, 10 )->create();
        for ($i=1; $i <= Lineprocess::count() ; $i++) { 
            # code...
            $scanner = new Scanner;
            $scanner->line_id = 1;
            $scanner->lineprocess_id = $i;        
            $scanner->name = 'Scanner '.$i;
            $scanner->mac_address = '65:C7:85:L9 (temp)';
            $scanner->ip_address = $i; //localhost in ipv6
            $scanner->save();
        }
    }

}
