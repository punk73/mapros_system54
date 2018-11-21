<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;
use App\LineprocessStart;
use Dingo\Api\Exception\StoreResourceFailedException;

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

	public function startId(){
		$start_id = LineprocessStart::select('start_id')->where('lineprocess_id', $this->id )->first();
		if (!$start_id) { //if doesn't exists;
			throw new StoreResourceFailedException("start_id dengan lineprocess_id = '{$this->id}' tidak ditemukan, tolong segera input. ", [
				'lineprocess_id' => $this->id,
			]);
			
			// return null;
		}else{
			return $start_id['start_id'];
		}
	}
}
