<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class Part extends Model
{
	protected $table = 'parts';
	
	protected $fillable = [
		'barcode',
		'guid_master',
		'guid_ticket',
		'scanner_id',
		'status',
		'scan_nik',
		'judge',
	];

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
			'barcode' => $this->barcode,
			'guid_master' => $this->guid_master,
			'guid_ticket'	=> $this->guid_ticket,
			'scanner_id' => $this->scanner_id,
			'status' => $this->status,
			'scan_nik' => $this->scan_nik,
			'scanner_id' => $this->scanner_id,
			'judge' => $this->judge,
		]);
	}
}
