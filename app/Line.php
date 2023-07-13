<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class Line extends Model implements LogsActivityInterface
{
    protected $table = 'lines';

	// use LogsActivity; //use Logs traits

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
