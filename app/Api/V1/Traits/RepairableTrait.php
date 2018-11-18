<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Model;


trait RepairableTrait {
	
	public function getJoinQuery(Model $modelParam = null){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;

		$query = $model->select([
			'lineprocesses.id'
		])->join('scanners', 'scanners.id','=','scanner_id')
		->leftJoin('lineprocesses', 'lineprocesses.id', '=', 'lineprocess_id');

		return $query;
	}

	public function getLineprocessNg(Model $modelParam = null, $uniqueColumnParam = null , $uniqueIdParam = null ){
		$model = (is_null($modelParam)) ? $this->getModel() : $modelParam ;
		$uniqueColumn = (is_null($uniqueColumnParam)) ? $this->getUniqueColumn() : $uniqueColumnParam ;
		$uniqueId = (is_null($uniqueIdParam)) ? $this->getUniqueId() : $uniqueIdParam ;

		return $this->getJoinQuery($model)
		->where( $uniqueColumn , $uniqueId )
		->where('judge', 'NG')->orderBy('created_at', 'desc')
		->first();
	}

	public function isAfterNgProcess(){
		$lineprocessNg = $this->getLineprocessNg();
		if (is_null($lineprocessNg)) {
			# record NG tidak ditemukan. belum tahu harus bagaimana;
		}

		$process = explode(',', $this->getProcess() );

	}
}