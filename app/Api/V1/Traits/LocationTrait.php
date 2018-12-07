<?php
namespace App\Api\V1\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;
use App\Location;
use App\Board;
use App\Symptom;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\BoardLocation;

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
	public function insertLocation(Board $board, Array $locationsParam = null ){
		/*verify parameter from users*/
		if(is_null($locationsParam)) { 
			$locations = $this->getLocations();
			if (is_null( $locations) ) {
				return false; //insertLocation error 
			}
		}
		else{ 
			if (!$this->verifyLocations($locationsParam)) {
				throw new StoreResourceFailedException("location parameter is not correct!", [
					'locations_param' => $locationsParam,
					'expected_param' => [
						[
							'ref_number_id'=>1,
							'symptoms_id' => [1,2,3]

						]
					],
					'messages' => 'expected_param is just an example'
				]);
			}

			$locations = $locationsParam;
		};

		foreach ($locations as $key => $location) {
			# code...
			$locationId = $location['ref_number_id'];
			$locationSymptoms = $location['symptoms_id'];
			
			// input ref_number_id dengan boards;
			$pivot = $this->saveBoardLocation($board, $locationId );
			// for every pivot save, collect the id of the pivot,
			$this->saveLocationSymptoms($pivot, $locationSymptoms );
			// save into second pivot;
		}
	}

	/*this will return pivot id */
	/*$location id must be single integer */
	public function saveBoardLocation(Board $board, $location_id){
		$board->locations()->attach($location_id);

		$locations = $board->locations;
		foreach ($locations as $key => $location) {
			// because location_id is single id (not an array), it save to keep it this way;
			return $location->pivot; //return the pivot model
		}
	}

	public function saveLocationSymptoms(BoardLocation $pivot, Array $symptoms ){
		return $pivot->symptoms()->attach($symptoms);
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