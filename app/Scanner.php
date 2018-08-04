<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;


class Scanner extends Model implements LogsActivityInterface
{	
	use LogsActivity; //use Logs traits

    protected $table = 'scanners';

    public function getActivityDescriptionForEvent($eventName)
	{
	    if ($eventName == 'created')
	    {
	        return $this->table .' "'. $this->name . '" was created';
	    }

	    if ($eventName == 'updated')
	    {
	        return $this->table .' "'. $this->name . '" was updated';
	    }

	    if ($eventName == 'deleted')
	    {
	        return $this->table .' "'. $this->name . '" was deleted';
	    }

	    return '';
	}

}
