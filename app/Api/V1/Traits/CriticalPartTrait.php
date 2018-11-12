<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Critical;
use App\CriticalNode;
use App\Scanner;
use DB;

trait CriticalPartTrait {
	protected $criticalParts; //it is string or array of string; but mostly it will array
	protected $extractedCriticalParts; //array of extracted critical parts
	protected $errorIndex;
	/*public function save(){
	}*/

	public function setCriticalPart($criticalParts){
		if (!$this->isCriticalPartExtracted($criticalParts)) {
			# jika belum di extract;
			$this->extractedCriticalParts = $this->extractCriticalPart($criticalParts);
			if ($this->isCriticalPart($this->extractedCriticalParts) == false ) {
				# kalau bukan critical parts, throw error
				$critical = json_encode($criticalParts);
				throw new StoreResourceFailedException("{$critical} BUKAN CRITICAL PARTS, TIDAK UDAH SCAN.", [
					'criticalParts' => $criticalParts,
					'extractedCriticalParts' => $this->extractedCriticalParts,
				]);
			}
		}
		$this->criticalParts = $criticalParts;
	}
	
	public function getCriticalPart(){
		return $this->criticalParts;
	}

	public function setErrorIndex($index){
		$this->errorIndex = $index;
	}

	public function getErrorIndex(){
		return $this->errorIndex;
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
		/*$extracted pasti array, karena sudah di extract. don't need to make else statement*/
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
		// salah satu ada, maka critical parts;
		return ( $extracted['production_date'] !='' || $extracted['lotno'] !='' );
	}

	/*no longer need it because it is using firstOrNew() */
	/*public function isCriticalExists($criticalPart){
		if (!$this->isCriticalPartExtracted($criticalPart)) {
			$criticalPart = $this->extractCriticalPart($criticalPart);
		}

		return Critical::where('unique_id', $criticalPart['unique_id'])
		->where('part_no', $criticalPart['part_no'])
		->where('po', $criticalPart['po'])
		->exists();
	}*/

	public function insertIntoCritical($extractedCriticalParts, $uniqueId = null , $criticalScannerData = null ){
		
		if (is_null($extractedCriticalParts)) {
			$extractedCriticalParts = $this->extractedCriticalParts;
		}

		if (!$this->isCriticalPartExtracted( $extractedCriticalParts)) {
			/*kalau belum di extract, extract dulu */
			/*string jadi associative array*/
			$extractedCriticalParts = $this->extractCriticalPart($extractedCriticalParts);
		}

		/*default value for criticalScannerData, mostly akabn masuk sini. */
		/*ini dibuat parameter untuk automatic testing*/
		if (is_null($criticalScannerData)) {
			$ipScanner = (isset($this->parameter)) ? $this->parameter : ['ip' => '17DA14']; //default value
			$criticalScannerData = $this->getCriticalScannerData($ipScanner);
			$criticalScannerData = $criticalScannerData->toArray();
			$criticalScannerData['scan_nik'] = (isset($ipScanner['nik'])) ? $ipScanner['nik'] : 'nik_tmp';
		}
		
		if (is_null($uniqueId) ) {
			$uniqueId = $this->getUniqueId(); //dari node
		}

		# cek if its not assoc array ( array of extracted critical parts )
		if (!$this->isAssoc($extractedCriticalParts) ) {
			foreach ($extractedCriticalParts as $key => $extractedValue ) {
				$extracted = array_merge($extractedValue, $criticalScannerData);
				// cek apakah critical parts sudah di save atau baru.
				$critical = Critical::firstOrNew($extracted);
				if (!$critical->exists) {
					# if it's not exists yet
					$critical->save();
				}

				$criticalId = $critical->id;
				$this->setErrorIndex($key); //it'll always have a value; 
				$saveResult = $this->saveToPivot($criticalId, $uniqueId);
				
			}
		}else{
			/*
				kalau masuk sini, artinya critical part itu cuman satu. dan sudah di extract;
				bukan array dari extracted critical part jadi tidak harus pakai foreach;
			*/
			$extracted = array_merge($extractedCriticalParts, $criticalScannerData);
			// cek apakah critical parts sudah di save atau baru.
			$critical = Critical::firstOrNew($extracted);
			if (!$critical->exists) {
				# if it's not exists yet
				$critical->save();
			}

			$criticalId = $critical->id;
			$this->setErrorIndex(0); //we need to determines
			$saveResult = $this->saveToPivot($criticalId, $uniqueId);
			
		}

		return true; //save method run expectedly;
	}

	private function getCriticalScannerData($parameter, $debug = false){
		if (!$debug) {
			/*its for real purposes*/
			return Scanner::select([
				// 'scanners.id',
				'lines.id as line_id',
				'lineprocesses.id as lineprocess_id',
			])
			->where('ip_address', $parameter['ip'])
			->leftJoin('lines', 'scanners.line_id', '=', 'lines.id')
			->leftJoin('lineprocesses', 'scanners.lineprocess_id', '=', 'lineprocesses.id')
			->first();
		}else{
			return $parameter;
		}
	}

	public function saveToPivot($criticalPartId, $uniqueId){
		$status = (isset($this->status )) ? $this->getStatus() : 'IN'; //default nya IN

		if( $this->isRunOut($criticalPartId, $status ) ){
			// return false; // jika habis, return false sebagai indikasi bahwa ada error;
			$errorIndex = $this->getErrorIndex();
			$criticals = $this->getCriticalPart(); //it can be string or array
			$criticalPartError = ( is_array( $criticals ) )? $criticals[$errorIndex] : $criticals ;
			throw new StoreResourceFailedException("Critical Part habis. Mohon ganti No Critical Part berikut : '{$criticalPartError}'. ", [
				'critical_parts' => $this->getExtractedCriticalParts(),
				'critical_error' => $criticalPartError,
				'critical_error_index' => $errorIndex,
				// 'node' => json_decode($this, true),
			]);
		}

		$pivot = CriticalNode::firstOrNew([
			'critical_id' => $criticalPartId,
			'unique_id' => $uniqueId,
		]);
		/*save hanya jika critical & unique_id is not exists before;*/
		/*kalau ga gini, nanti pas scan out ke save lagi, jdnya salah.*/
		if (!$pivot->exists) {
			$pivot->save();
		}
	}

	/*cek apakah masih ada sisa*/
	public function isRunOut($criticalPartId, $status = 'OUT'){
		if ($status == 'OUT') {
			return false;
		}
		/*cek if critical with specific id exists, */
		if ( Critical::where('id', $criticalPartId)->first() === null ) {
			# kalau masuk sini artinya critical_node dengan critical_id = $criticalPartId tidak ditemukan;
			throw new StoreResourceFailedException("Critical parts dengan id = {$criticalPartId} tidak ditemukan", [
				'critical_parts' => $this->getCriticalPart(),
			]);
		}
		// Critical::select('qty')
		$result = CriticalNode::select(DB::raw('( COUNT(critical_node.critical_id) >= criticals.qty ) as isRunOut'))
		->join('criticals', 'criticals.id','=','critical_node.critical_id')
		->where('critical_id', $criticalPartId )
		->groupBy('critical_id', 'criticals.qty')
		->first();

		/*jika isRunOut == 1, maka sudah habis, dan sebaliknya*/
		/*
			kalau result return null, artinya critical_node masih kosong;
		*/
		

		return ($result['isRunOut'] == 1 );
	}
}