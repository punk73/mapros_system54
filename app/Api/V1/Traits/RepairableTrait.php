<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Model;


trait RepairableTrait {
	
	public function getJoinQuery(Model $modelParam = null){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;

		$query = $model->select([
			'lineprocesses.id'
		])
		->join('scanners', 'scanners.id','=','scanner_id')
		->leftJoin('lineprocesses', 'lineprocesses.id', '=', 'lineprocess_id');
		// ->orderBy('created_at', 'desc');
		return $query;
	}

	public function getLineprocessNg(Model $modelParam = null, $uniqueColumnParam = null , $uniqueIdParam = null ){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;
		/*uniqueColumn different from one another. it can be board_id, guid_master, or guid_ticket based on the model_type*/
		$uniqueColumn = (is_null($uniqueColumnParam)) ? $this->getUniqueColumn() : $uniqueColumnParam ;
		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam ;

		$result = $this->getJoinQuery($model)
		->where( $uniqueColumn , $uniqueId )
		->where('judge', 'NG')
		->first();

		if (!$result) {
			// return null;
			/*disini result bernilai false, artinya record dengan judge NG tidak ditemukan*/
			throw new StoreResourceFailedException("Record NG tidak ditemukan. klik detail untuk info selengkapnya", [
				'node' => json_decode(json_encode( $this), true ),
			]);
		}
	
		return $result['id'];
	}

	/*
		it's should return boolean
		the parameter is not necesarry, it is for testing purpose. called dependecies injections;
	*/
	public function isAfterNgProcess($processParam = null, $lineprocessId = null , $lineprocessNgParam = null ){
		$lineprocessNg = (is_null($lineprocessNgParam)) ? $this->getLineprocessNg() : $lineprocessNgParam;

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
}