<?php

use Illuminate\Database\Seeder;
use App\MasterCritical;

class MasterCriticalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = $this->getData();

        foreach ($datas as $key => $data) {
			$model = new MasterCritical($data);
			$model->save();        	
        }

        // fwrite(STDOUT, var_dump($this->getData()));
    }

    private function getData(){
    	$data = [
    		['Audio',	'Top Cover',	'A5C-0056-04',	'SHC'],
			['Audio',	'Panel Assy',	'A6D-0136-**',	'SHC'],
			['Audio',	'Front Glass',	'B1A-0099-**',	'CHIYODA'],
			['Audio',	'MCU IC',		'CY8C04025LS412',	'Cypress'],
			['Audio',	'Conductive Rubber',	'E2K-0081-**',	'KWOK'],
			['Audio',	'Cover',		'F0G-0312-**',	'SHC'],
			['Audio',	'LCD Case',		'F1C-0152-**',	'SHC'],
			['Audio',	'PW Board',		'J7J-0386-**',	'DRACO'],
			['Audio',	'PWB',			'J7J-0506-**',	'DRACO'],
			['Audio',	'PW Board',		'J7J-0532-**',	'DRACO'],
			['Audio',	'Touch Panel',	'W0C-0387-**',	'MOLEX'],
			['DA',		'Chassis',		'A1A-0140-**',	'SHC'],
			['DA',		'Side Plate',	'A4J-0039-**',	'SHC'],
			['DA',		'Side Plate',	'A4J-0040-**',	'SHC'],
			['DA',		'Panel',		'A6C-0119-**',	'SHC'],
			['DA',		'Front Glass',	'B1A-0100-**',	'SHC'],
			['DA',		'Front Glass',	'B1A-0101-**',	'SHC'],
			['DA',		'Cover',		'F0G-0314-**',	'SHC'],
			['DA',		'Holder',		'J1K-0814-**',	'SHC'],
			['DA',		'PCB（MAIN',		'J7J-0519-00',	'DRACO'],
			['DA',		'PCB（AUDIO)',	'J7J-0520-00',	'DRACO'],
			['DA',		'PCB PANEL',	'J7J-0521-00',	'DRACO'],
			// ['DA',		'Touch Panel',	'W0C-0388-00',　 "Go world" ],
			// ['DA',		'Touch Panel',	'W0C-0389-00',　 "Go world" ],

    	];

    	$newData = [];
    	foreach ($data as $key => $item) {
    		$newItem = [];
    		$newItem['linetype_id'] = ($item[0] == 'Audio') ? 1:2;
    		$newItem['partname'] = $item[1];
    		$newItem['partno'] = $item[2];
    		$newItem['supplier'] = $item[3];
    		$newItem['qty_request'] = 50; //dummy
    		$newData[] = $newItem;
    	}
    	return $newData;
    }
}
