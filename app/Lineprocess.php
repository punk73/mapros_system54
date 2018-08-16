<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class Lineprocess extends Model implements LogsActivityInterface
{
    protected $table = 'lineprocesses';

    protected $fillable = [
    	'name','type','std_time',
    ];

    use LogsActivity; //use Logs traits

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

	public function columnSettings(){
		return $this->belongsToMany('App\ColumnSetting');
	}
}
