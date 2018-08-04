<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class Department extends Model implements LogsActivityInterface
{
    use LogsActivity;

    protected $table = 'departments';

    public function getActivityDescriptionForEvent($eventName)
	{
	    if ($eventName == 'created')
	    {
	        return 'Department "' . $this->name . '" was created';
	    }

	    if ($eventName == 'updated')
	    {
	        return 'Department "' . $this->name . '" was updated';
	    }

	    if ($eventName == 'deleted')
	    {
	        return 'Department "' . $this->name . '" was deleted';
	    }

	    return '';
	}

    public function users(){
    	return $this->hasMany('App\User');
    }
}
