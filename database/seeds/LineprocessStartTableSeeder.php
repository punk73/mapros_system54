<?php

use Illuminate\Database\Seeder;
use App\Lineprocess;
use App\LineprocessStart;

class LineprocessStartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
    	$lineprocesses = $this->getData();
        foreach ($lineprocesses as $key => $lineprocess ) {
        	$id = $lineprocess->id;
        	LineprocessStart::insert([
        		'lineprocess_id' => $id,
        		'start_id' => $id,
        	]);
        }
    }

    public function getData(){
    	$data = Lineprocess::all();
    	return $data;
    }
}
