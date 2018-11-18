<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;

trait RepairableTrait {
	
	public function getLineprocessNg(){
		return $this->getModel()
		->where( $this->getUniqueColumn() , $this->$this->getUniqueId() )
		->where('judge', 'NG')->orderBy('created_at', 'desc')
		->first();
	}

	public function isAfterNgProcess(){

	}
}