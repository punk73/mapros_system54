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
	        return $this->table .' "'. $this->partno . '" was created';
	    }

	    if ($eventName == 'updated')
	    {
	        return $this->table .' "'. $this->partno . '" was updated';
	    }

	    if ($eventName == 'deleted')
	    {
	        return $this->table .' "'. $this->partno . '" was deleted';
	    }

	    return '';
	}
}
