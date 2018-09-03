<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class Critical extends Model
{
    protected $table = 'criticals';

    protected $fillable = [
    	'line_id',
		'lineprocess_id',
		'unique_id',
		'supp_code',
		'part_no',
		'po',
		'production_date',
		'lotno',
		'qty',
		'scan_nik',
    ];

    public function getActivityDescriptionForEvent($eventName)
	{
	    if ($eventName == 'created')
	    {
	        return $this->table .' "'. $this->part_no . '/' . $this->po .'/'. $this->qty .'/'. $this->supp_code .'/'. $this->unique_id .'/'. $this->production_date .'/'. $this->lotno  . '" was created';
	    }

	    if ($eventName == 'updated')
	    {
	        return $this->table .' "'. $this->part_no . '/' . $this->po .'/'. $this->qty .'/'. $this->supp_code .'/'. $this->unique_id .'/'. $this->production_date .'/'. $this->lotno  . '" was updated';
	    }

	    if ($eventName == 'deleted')
	    {
	        return $this->table .' "'. $this->part_no . '/' . $this->po .'/'. $this->qty .'/'. $this->supp_code .'/'. $this->unique_id .'/'. $this->production_date .'/'. $this->lotno  . '" was deleted';
	    }

	    return '';
	}

	private function getData(){
		return json_encode([
			'line_id' => $this->line_id,
			'lineprocess_id' => $this->lineprocess_id,
			'unique_id' => $this->unique_id,
			'supp_code' => $this->supp_code,
			'part_no' => $this->part_no,
			'po' => $this->po,
			'production_date' => $this->production_date,
			'lotno' => $this->lotno,
			'qty' => $this->qty,
			'scan_nik' => $this->scan_nik,
		]);
	}
}
