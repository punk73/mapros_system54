<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Model;
use App\Lineprocess;
use App\Repair;
trait RepairableTrait {

	public function getJoinQuery(Model $modelParam = null){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;
		$table = $model->getTable(); //it get table name from the models; it can be masters, boards, or tickets;

		$query = $model->select([ 
			'lineprocesses.id'
		])
		->join('scanners', 'scanners.id','=','scanner_id')
		->leftJoin('lineprocesses', 'lineprocesses.id', '=', 'lineprocess_id')
		->orderBy( $table.'.created_at', 'desc'); //return yg paling baru dibuat record NG nya (in case ada lebih dari satu);
		return $query;
	}

	/*
	* return process that >= current lineprocess id;
	* if the process is 1,2,3,4,5 and the lineprocess is 3 then it will return = 3,4,5
	*/
	public function getNextProcess($currentId = null, $processParam = null ){
		// default lineprocess id parameter
		$currentLineProcessId = (is_null($currentId))?$this->getLineprocess()->id : $currentId;
		// default process parameter
		$process = (is_null($processParam)) ? $this->getProcess(): $processParam;
		$process = explode(',', $process );
		// index array 
		$lineprocessIndex = array_search($currentLineProcessId, $process );
		
		for ($i=0; $i < $lineprocessIndex ; $i++) { 
			# code...
			unset($process[$i]);
		}

		$process = array_values($process);

		return $process;
	}

	public function getLineprocessNg(Model $modelParam = null, $uniqueColumnParam = null , $uniqueIdParam = null,Array $nextProcessParam = null ){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;
		/*uniqueColumn different from one another. it can be board_id, guid_master, or guid_ticket based on the model_type*/
		$uniqueColumn = (is_null($uniqueColumnParam)) ? $this->getUniqueColumn() : $uniqueColumnParam ;
		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam ;
		
		// get process after or equal this current lineprocess_id;
		$nextProcess = (is_null($nextProcessParam))? $this->getNextProcess() : $nextProcessParam;

		$result = $this->getJoinQuery($model)
		->where( $uniqueColumn , $uniqueId )
		->whereIn('lineprocesses.id', $nextProcess )
		->where('judge', 'NG')
		->first();

		if (!$result) {
			return null;
			/*disini result bernilai false, artinya record dengan judge NG tidak ditemukan*/
			/*throw new StoreResourceFailedException("Record NG tidak ditemukan. klik detail untuk info selengkapnya", [
				'node' => json_decode(json_encode( $this), true ),
			]);*/
		}

		return $result['id'];
	}

	public function getFurthestNgProcess(Model $modelParam = null, $uniqueColumnParam = null , $uniqueIdParam = null, $processParam = null ){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;
		/*uniqueColumn different from one another. it can be board_id, guid_master, or guid_ticket based on the model_type*/
		$uniqueColumn = (is_null($uniqueColumnParam)) ? $this->getUniqueColumn() : $uniqueColumnParam ;
		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam ;

		$lineprocesses = $this->getJoinQuery($model)
		->where( $uniqueColumn , $uniqueId )
		->where('judge', 'NG')
		->get();

		// return $lineprocesses;

		$process = (is_null($processParam)) ? $this->getProcess() : $processParam;
		$process = explode(',', $process );

		$indexProcess = -1; //index
		$result=null;
		/*jika lineprocesss null, maka foreach jg ga akan ter triger*/
		foreach ($lineprocesses as $i => $lineprocessNg) {
			$key = array_search($lineprocessNg->id , $process );
			if (!($key === false)) {
				// jika lineprocess id ditemukan
				if ( $key > $indexProcess ) {
					# jika ditemukan, 
					$indexProcess = $key;
					$result = $lineprocessNg->id;
				}
			}
		}
		// $result will be null if lineprocess Ng not found in array process;
		return $result;
	}

	public function getLineprocessNgName($idParam = null ){
		$id = (is_null($idParam)) ? $this->getLineprocessNg() : $idParam;
		$lineprocess = Lineprocess::select(['name'])->find($id);

		if (!$lineprocess) {
			# code...
			throw new StoreResourceFailedException("Lineprocess NG tidak ditemukan!. klik see detail", [
				'id' => $id,
				'message' => "lineprocess dengan id = '{$id}' tidak ditemukan.",
			]);
		}

		return $lineprocess['name'];
	}

	/*
		it's should return boolean
		the parameter is not necesarry, it is for testing purpose. called dependecies injections;
	*/
	public function isAfterNgProcess($processParam = null, $lineprocessId = null , $lineprocessNgParam = null ){
		$lineprocessNg = (is_null($lineprocessNgParam)) ? $this->getFurthestNgProcess() : $lineprocessNgParam;

		$lineprocess = (is_null($lineprocessId))? $this->getLineprocess()->id : $lineprocessId ;

		$process = (is_null($processParam)) ? $this->getProcess() : $processParam;
		$process = explode(',', $process );

		$lineprocess_index = array_search($lineprocess, $process);
		/* === is necesarry due to we sometimes used 1 as parameter */
		if ( $lineprocess_index === false ) {
			# lineprocess index tidak ditemukan di process
			throw new StoreResourceFailedException("lineprocess id tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'lineprocess' => $lineprocess,
				'process' => $process
			]);
		}

		$ng_process_index  = array_search($lineprocessNg, $process);
		/* === is necesarry due to we sometimes used 1 as parameter */
		if ($ng_process_index === false ) {
			# ng process tidak ditemukan di array process;
			throw new StoreResourceFailedException("lineprocess NG tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'lineprocess_ng' => $lineprocessNg,
				'process' => $process
			]);
		}

		/*return [
			'lineprocess_id' => $lineprocess,
			'lineprocessNg' => $lineprocessNg,
			'process' => $process,
			'lineprocess_index' => $lineprocess_index,
			'ng_process_index'  => $ng_process_index,
		];*/

		/*akan return true kalau lineprocess index lebih besar dari lineprocess NG*/
		return ( $lineprocess_index > $ng_process_index );
	}

	public function getStartId($idParam = null ){
		// Start id yg di return adalah start id dari process dimana si NG itu terjadi.
		// bukan dari current lineprocess.
		/*
			contoh : NG terjadi di inspect 3. maka start id yg di return adalah start id inspect 3.
			bukan start id current lineprocess.
		*/

		$id = (is_null($idParam))? $this->getLineprocessNg() :$idParam;

		$data = Lineprocess::find($id);

		if (is_null($data)) {
			throw new StoreResourceFailedException("Lineprocess with id = {$id} is not found.", [
				'lineprocess_id' => $id,
			]);
		}

		$data = $data->startId();
		return $data;
	}

	/*
	* isRepaired is function to check data in table repair;
	* the return value is boolean;
	*/
	public function isRepaired($uniqueIdParam = null, $lineprocessNgIdParam = null ){
		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam;

		$repairExists = Repair::where('unique_id', $uniqueId )
		->orderBy('created_at', 'desc')
		->first();

		if (!$repairExists) {
			# jika repair record tidak ketemu
			return false;
		}

		$lineprocessNgId = (is_null($lineprocessNgIdParam))? $this->getLineprocessNg() : $lineprocessNgIdParam;
		if (is_null($lineprocessNgId)) {
			/*jika NG Records tidak ketemu*/
			return false;
		}

		if (is_null($repairExists->ng_lineprocess_id) ) {
			# code...
			$repairExists->ng_lineprocess_id = $lineprocessNgId;
			$repairExists->save();
		}

		return ($repairExists->ng_lineprocess_id === $lineprocessNgId );
			
		/*
			method sekarang masih sama dengan method sebelumnya, hanya saja ditambahkan satu lagi pengecekan. yaitu:
			- cek kalau NG sekarang, sama kaya Rework skrg
		*/
		// $lineprocessNg = (is_null($isLineprocessNgExists))?(is_null($lineprocessNgId)===false):$isLineprocessNgExists;
		
		// return ($repairExists && $lineprocessNg );
	}

	public function isBeforeStartId($processParam = null , $lineprocessId = null, $startIdParam = null){
		/*setup default value of the parameter*/
			/*the parameter is use in unit testing. it's called dependencies injections*/
			$process = (is_null($processParam)) ? $this->getProcess() : $processParam;
			$process = explode(',', $process );

			$lineprocess = (is_null($lineprocessId))? $this->getLineprocess()->id : $lineprocessId ;
			$startId = (is_null($startIdParam)) ? $this->getStartId() : $startIdParam;
		/*end*/
		$lineprocess_index = array_search($lineprocess, $process);
		/* === is necesarry due to we sometimes used 1 as parameter */
		if ( $lineprocess_index === false ) {
			# lineprocess index tidak ditemukan di process
			throw new StoreResourceFailedException("lineprocess id tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'lineprocess' => $lineprocess,
				'process' => $process
			]);
		}

		$startid_index = array_search($startId, $process);
		if ($startid_index === false) {
			# code...
			throw new StoreResourceFailedException("start id '{$startId}' tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'start_id' => $startId,
				'process' => $process
			]);
		}

		/*will return true if lineprocess_index kurang dari startid_index*/
		return ($lineprocess_index < $startid_index );

	}

	public function isBeforeOrEqualStartId($processParam = null , $lineprocessId = null, $startIdParam = null){
		/*setup default value of the parameter*/
		/*the parameter is use in unit testing. it's called dependencies injections*/
			$process = (is_null($processParam)) ? $this->getProcess() : $processParam;
			$process = explode(',', $process );

			$lineprocess = (is_null($lineprocessId))? $this->getLineprocess()->id : $lineprocessId ;
			$startId = (is_null($startIdParam)) ? $this->getStartId() : $startIdParam;
		/*end*/
		$lineprocess_index = array_search($lineprocess, $process);
		/* === is necesarry due to we sometimes used 1 as parameter */
		if ( $lineprocess_index === false ) {
			# lineprocess index tidak ditemukan di process
			throw new StoreResourceFailedException("lineprocess id tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'lineprocess' => $lineprocess,
				'process' => $process
			]);
		}

		$startid_index = array_search($startId, $process);
		if ($startid_index === false) {
			# code...
			throw new StoreResourceFailedException("start id '{$startId}' tidak ditemukan di proses. klik detail untuk info selengkapnya", [
				'start_id' => $startId,
				'process' => $process
			]);
		}

		/*will return true if lineprocess_index kurang dari startid_index*/
		return ($lineprocess_index <= $startid_index );
	}

	public function reworkCount(Model $modelParam = null, $scannerIdParam = null, $uniqueColumnParam = null, $uniqueIdParam = null, $status = null ){
		$model 	 		= (is_null($modelParam)) ? $this->getModel() 				: $modelParam;
		$scannerId 		= (is_null($scannerIdParam)) ? $this->getScanner()->id 		: $scannerIdParam;
		$uniqueColumn 	= (is_null($uniqueColumnParam))? $this->getUniqueColumn() 	: $uniqueColumnParam;
		$uniqueId 		= (is_null($uniqueIdParam)) ? $this->getUniqueId()			: $uniqueIdParam;

		// get how many rework record with specific scanner id;
		$recordRework = $model->where($uniqueColumn, $uniqueId )
		->where('scanner_id', $scannerId )
		->where('judge', 'REWORK');

		if (!is_null($status)) {
			# code...
			$recordRework = $recordRework->where('status', $status );
		}

		$recordRework = $recordRework->count();

		return $recordRework;
	}

	public function getAllNgRecord(Model $modelParam = null, $uniqueColumnParam = null, $uniqueIdParam = null){
		$model 	 		= (is_null($modelParam)) ? $this->getModel() 				: $modelParam;
		$uniqueColumn 	= (is_null($uniqueColumnParam))? $this->getUniqueColumn() 	: $uniqueColumnParam;
		$uniqueId 		= (is_null($uniqueIdParam)) ? $this->getUniqueId()			: $uniqueIdParam;

		$result = $model
		->select([
			$model->getTable(). '.*',
			'lineprocess_id',
			'scanners.name as scanner_name',

		])
		->join('scanners', $model->getTable().'.scanner_id', '=', 'scanners.id')
		->where($uniqueColumn, $uniqueId)
		->where('judge', 'NG')
		->orderBy($model->getTable(). '.created_at', 'desc')
		->get();

		return $result;
	}

	public function hasRework($recordReworkParam = null, $ngRecordsParam = null, $lineprocessParamId = null, $processParam = null ){
		// get how many rework record with specific scanner id;
		$recordRework = (is_null($recordReworkParam))? $this->reworkCount() : $recordReworkParam;

		// get all lineprocess_ng record with specific uniqueColumn (guid_ticket, guid_master, '') 
		$ngRecords = (is_null($ngRecordsParam))? $this->getAllNgRecord() : $ngRecordsParam;

		// get current process
		$process = (is_null($processParam)) ? $this->getProcess() : $processParam;
		// get current lineprocess
		$currentLineProcessId = (is_null($lineprocessParamId))? $this->getLineprocess()->id : $lineprocessParamId ;
		$recordNgAfterCurrentProcess = 0;
		foreach ($ngRecords as $key => $ngRecord ) {
			# code...
			$ngRecordId = $ngRecord['lineprocess_id'];
			if ($this->isBeforeOrEqualStartId($process, $currentLineProcessId, $ngRecordId )) {
				# code...
				$recordNgAfterCurrentProcess++;
			}
		}
		/*untuk tiap record NG, dibutuhkan sepasang record rework. yaitu (IN & OUT)*/
		$result = ( ($recordRework/2) >= $recordNgAfterCurrentProcess );

		/*return [
			'recordRework' => $recordRework,
			// 'ceking' => $ceking,	
			'recordNgAfterCurrentProcess' => $recordNgAfterCurrentProcess,
			'result' => $result
		];*/
		return $result;
	}

	public function repairCount($uniqueIdParam = null ){

		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam;

		$repairCount = Repair::where('unique_id', $uniqueId )->count();
		return $repairCount;
	}

	public function isReworked($reworkCount = null, $repairCount = null ){
		$reworkRecords = (is_null($reworkCount)) ? $this->reworkCount(null, null, null, null, 'OUT') : $reworkCount;
		$repairRecords = (is_null($repairCount)) ? $this->repairCount() : $repairCount;
		
		// untuk handle pokayoke di repair kedua;
		return ( $reworkRecords < $repairRecords );
	}

}