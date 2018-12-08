<?php

use Illuminate\Database\Seeder;
use App\ModelHeader;
use App\Pwb;
use App\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //we need to seed three table here.
        // ModelHeader, Pwb, Location;
    	$data = $this->getData();
    	foreach ($data as $modelname => $value) {
    		#save to model_headers;
            $modelHeader = new ModelHeader;
            $modelHeader->name = $modelname;
            $modelHeader->save();
            foreach ($value as $pwbname => $arrayOfLocations) {
                # save to Pwb
                $pwb = new Pwb;
                $pwb->model_header_id = $modelHeader->id;
                $pwb->name = $pwbname;
                $pwb->save();
                foreach ($arrayOfLocations as $key => $refNo ) {
                    # save to $refNo
                    $location = new Location;
                    $location->pwb_id = $pwb->id;
                    $location->ref_no = $refNo;
                    $location->save();
                }
            }
    	}

    }

    public function getData(){
    	return [
    		'DDXGT' => [
    			'MAIN' 		 => [
    				'J7000',
					'J802',
					'J803',
					'CN1',
					'CN251',
					'C7',
    			], 
    			'VIDEO UNIT' => [
    				'BRACKET (For IC101)',
					'BRACKET (For IC201)',
					'IC101',
					'J3',
					'J701',
					'J702',
					'IC201',
					'REAR PANEL',
					'W502,W702',
					'W501,W701',
					'MOUNTING',
					'E501',
					'A501',
					'CN151',
					'L1',
					'C1',
    			],
    			'AUDIO UNIT' => [
    				'J1',
					'J2',
    			],
    		], 
    		'DPXGT' => [
    			'MAIN UNIT'  => [
    				'C551',
					'C990',
					'CN701',
					'CN704',
					'CN990',
					'CN3000',
					'IC301',
					'IC901',
					'J601',
					'L990',
					'REAR PANEL'
    			], 
    			'VIDEO UNIT' => [
    				'IC90',
					'J60',
					'J80',

    			], 
    			'SWRC UNIT'  => [
    				'CN1',
					'CN3',
    			],
    		]
    	];
    }
}
