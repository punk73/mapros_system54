<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class MasterCritical extends Model
{
    protected $table = 'master_criticals';

    protected $fillable = [
    	'linetype_id',
		'partname',
		'partno',
		'supplier',
		'qty_request',
    ];
    // protected $name = $this->partno;
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
