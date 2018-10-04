<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class Master extends Model
{
    protected $table = 'masters';

    public function getActivityDescriptionForEvent($eventName)
	{
	    if ($eventName == 'created')
	    {
	        return $this->table .' "'. $this->getData() . '" was created';
	    }

	    if ($eventName == 'updated')
	    {
	        return $this->table .' "'. $this->getData() . '" was updated';
	    }

	    if ($eventName == 'deleted')
	    {
	        return $this->table .' "'. $this->getData() . '" was deleted';
	    }

	    return '';
	}

	private function getData(){
		return json_encode([
			'ticket_no_master' => $this->ticket_no,
			'guid_master' => $this->guid_master,
			'scanner_id' => $this->scanner_id,
			'status' => $this->status,
			'scan_nik' => $this->scan_nik,
			'scanner_id' => $this->scanner_id,
			'serial_no'	=> $this->serial_no,
			'judge' => $this->judge,

		]);
	}

	public function symptoms(){
		return $this->belongsToMany('App\Symptom');
	}
}
