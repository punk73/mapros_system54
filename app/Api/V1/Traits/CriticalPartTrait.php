<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Critical;

trait CriticalPartTrait {
	protected $criticalParts; //it is string or array of string; but mostly it will array
	protected $extractedCriticalParts; //array of extracted critical parts

	/*public function save(){
	}*/

	public function setCriticalPart($criticalParts){
		if (!$this->isCriticalPartExtracted($criticalParts)) {
			# jika belum di extract;
			$this->extractedCriticalParts = $this->extractCriticalPart($criticalParts);
			if ($this->isCriticalPart($this->extractedCriticalParts) == false ) {
				# kalau bukan critical parts, throw error
				throw new StoreResourceFailedException("this is not critical parts", [
					'criticalParts' => $this->criticalParts,
					'extractedCriticalParts' => $this->extractedCriticalParts,
				]);
			}
		}
		$this->criticalParts = $criticalParts;
	}
	
	public function getCriticalPart(){
		return $this->criticalParts;
	}

	public function getExtractedCriticalParts(){
		return $this->extractedCriticalParts;
	}

	public function extractCriticalPart($criticalPart){
		if (is_array($criticalPart) && ($this->isAssoc($criticalPart) == false) ) {
			# jika numerical array containing the string of critical parts ['B46-0825-00     2629991 200   I10775 B46-0825-00    201809021630379758000001          14124313451435435435465654645']
			$result = [];
			foreach ($criticalPart as $key => $part) {
				$result[] = $this->extract($part);
			}
			return $result;
		}else{
			return $this->extract( $criticalPart );
		}
	}

	protected function extract($criticalPart){
		$board_id = $criticalPart;
		$result = [];
		$result['part_no'] 			= substr($board_id, 0, 15);
		$result['po'] 				= trim( substr($board_id, 16, 7));
		$result['qty'] 				= trim( substr($board_id, 24, 5));
		$result['unique_id'] 		= trim( substr($board_id, 30, 46));
		$result['supp_code']		= trim( substr($board_id, 31, 6));
		$result['production_date'] 	= trim( substr($board_id, 77, 8));
		$result['lotno'] 			= trim( substr($board_id, 86, 20));
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
		/*
			$criticalPart = ['B46-0825-00     2629991 200   I10775 B46-0825-00    201809021630379758000001          14124313451435435435465654645'];	
		*/
		if (!$this->isCriticalPartExtracted($criticalPart) ) {
			$extracted = $this->extractCriticalPart($criticalPart);
		}else{
			$extracted = $criticalPart;
		}
		/*$extracted pasti array, don't need to make else statement*/
		if (is_array($extracted)) {
			if (!$this->isAssoc($extracted)) {
				$result = true;
				foreach ($extracted as $key => $value) {
					/*only return if isCritical($value) is false, if true, jangan dulu.*/
					if($this->isCritical($value) == false) {return false;}
				}
				return $result;
			}else{
				return $this->isCritical($extracted);
			}
		}
	}

	protected function isCritical($extracted){
		return ( $extracted['production_date'] !='' || $extracted['lotno'] !='' );
	}

	public function isCriticalExists($criticalPart){
		if (!$this->isCriticalPartExtracted($criticalPart)) {
			$criticalPart = $this->extractCriticalPart($criticalPart);
		}

		return Critical::where('unique_id', $criticalPart['unique_id'])
		->where('part_no', $criticalPart['part_no'])
		->where('po', $criticalPart['po'])
		->exists();
	}

	public function insertIntoCritical($extractedCriticalParts, $uniqueId ){
		
		if (is_null($extractedCriticalParts)) {
			$extractedCriticalParts = $this->extractedCriticalParts;
		}

		# cek if its assoc array
		if (!$this->isAssoc($extractedCriticalParts) ) {
			foreach ($extractedCriticalParts as $key => $extracted ) {
				// cek apakah critical parts sudah di save atau baru.
				$critical = new Critical($extracted);
			}
		}
	}
}