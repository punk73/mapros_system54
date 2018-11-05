<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Critical;

trait CriticalPartTrait {
	protected $criticalParts; //it is string or array of string; but mostly it will array
	protected $isCriticalPartExtracted; //whether or not critical parts is extracted

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

	/*
	* @parameter = array or string;
	*
	*/
	public function isCriticalPartExtracted($criticalPart){
		if (is_null($criticalPart)) {
			$criticalPart = $this->criticalParts;
		}
		// jika bukan array, pasti blm di extract; 
		if (!is_array( $criticalPart)) {
			return false;
		}else{
			// jika array
			$keys = [
				'part_no',
				'po',
				'qty',
				'unique_id',
				'supp_code',
				'production_date',
				'lotno',
			];

			if ($this->isAssoc($criticalPart)) {
				return $this->array_keys_exists($keys, $criticalPart );
			}else{
				/*jika numeric array, cek satu satu.*/
				$result = true;
				foreach ($criticalPart as $key => $part) {
					// satu aja bukan array, berarti belum di extract
					if (! is_array($part) ) {
						return false;
					}
					// satu aja array ga contain $keys, maka belum di extract
					if ($this->array_keys_exists($keys, $part ) == false ) {
						return false;
					}
				}

				return $result;
			}
		}
	}

	public function isAssoc(array $arr)
	{
	    if (array() === $arr) return false;
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}

	public  function array_keys_exists(array $keys, array $arr) {
	   return !array_diff_key(array_flip($keys), $arr);
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
		
		if (is_null($criticalPart)) {
			$criticalPart = $this->criticalParts;
		}

		$critical = new Critical($criticalPart);
		return $critical->save();
	}
}