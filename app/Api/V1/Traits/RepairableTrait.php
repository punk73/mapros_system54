<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;

trait RepairableTrait {
	
	public function getLineprocessNg(){
		return $this->model
		->where($this->unique_column, $this->unique_id )
		->where('judge', 'NG')->orderBy('created_at', 'desc')
		->first();
	}

	public function isAfterNgProcess(){

	}
}