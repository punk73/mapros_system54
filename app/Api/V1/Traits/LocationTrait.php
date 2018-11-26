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
	public function insertLocation(Array $locations = null ){

	}

	public function verifyLocations($locations){
		$result = true;

		if ( !(count($locations) > 0) ) {
			return false;
		}

		foreach ($locations as $key => $location) {
			# code...
			$locationId = $location['ref_number_id'];
			$locationSymptoms = $location['symptoms_id'];
			
			if (is_null( $locationId)) {
				return false;
			}

			if ( is_null($locationSymptoms) || !(count($locationSymptoms) > 0) ) {
				return false; // kalau error		
			}
		}

		return $result;
	}

	public function getLocations(){
		return $this->locations;
	}

	public function setLocations($locations){
		if (!$this->verifyLocations($locations)) {
			return false; //ga lolos verifikasi	
		}
			$this->locations = $locations;
	}

	public function decodeLocations($locations){

	}

}