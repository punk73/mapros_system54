<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Critical;

trait CriticalPartTrait {
	protected $criticalParts; //is it one record or more ???

	public function save(){

	}

	public function setCriticalPart($criticalParts){
		$this->criticalParts = $criticalParts;
	}
	
	public function getCriticalPart(){
		return $this->criticalParts;
	}
	
	public function extractCriticalPart($criticalPart){
		$board_id = $criticalPart;
		$result = [];
		$result['part_no'] 	= substr($board_id, 0, 15);
		$result['po'] 		= trim( substr($board_id, 16, 7));
		$result['qty'] 		= trim( substr($board_id, 24, 5));
		$result['unique_id'] 	= trim( substr($board_id, 30, 46));
		$result['supp_code']	= trim( substr($board_id, 31, 6));
		$result['production_date'] = trim( substr($board_id, 77, 8));
		$result['lotno'] = trim( substr($board_id, 86, 20));
		return $result;
	}
	
	public function isCriticalPart($criticalPart){
		$result = $this->extractCriticalPart($criticalPart);
		return ( $result['production_date'] !='' || $result['lotno'] !='' );
	}

	public function isCriticalExists($criticalPart){
		return Critical::where('unique_id', $criticalPart['unique_id'])
		->where('part_no', $criticalPart['part_no'])
		->where('po', $criticalPart['po'])
		->exists();
	}

	public function insertIntoCritical($criticalPart){
		$critical = new Critical($criticalPart);
		return $critical->save();
	}
}