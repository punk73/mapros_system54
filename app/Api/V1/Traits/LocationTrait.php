<?php
namespace App\Api\V1\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;
use App\Location;

trait LocationTrait {
	
	/*
	* the parameter sent from front_end will be
	* locations : [{
			ref_number_id : 1,
			symptoms_id:[1,2,3]
		},{
			ref_number_id : 2,
			symptoms_id: [2,3]
		}]
	*/
	public function insertLocation(Array $locations){

	}

	public function verifyLocations($locations){
		$result = true;
		foreach ($locations as $key => $location) {
			# code...
			$locationId = $location['ref_number_id'];
			$locationSymptoms = $location['symptoms_id'];

			if ( is_null($locationSymptoms)) {
				throw new StoreResourceFailedException("Tolong pilih symptom!!", [
					'location' => $this->locations //it should be registered in node class
				]);				
			}
		}

		return $result;
	}

	public function getLocations(){
		return $this->locations;
	}

	public function setLocations($locations){
		$this->locations = $locations;
	}

	public function decodeLocations($locations){

	}

}