<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class BaseModel extends Model implements LogsActivityInterface
{
	//  protected $table need to be define in children class

    // use LogsActivity; //use Logs traits

    public function getActivityDescriptionForEvent($eventName)
	{
	    if ($eventName == 'created')
	    {
			$text = $this->getTable() .' "'. $this->getData() . '" was created';
			return $this->checkTextLen($text);
	    }

	    if ($eventName == 'updated')
	    {
			$text = $this->getTable() .' "'. $this->getData() . '" was updated';
			return $this->checkTextLen($text);
	    }

	    if ($eventName == 'deleted')
	    {
			$text = $this->getTable() .' "'. $this->getData() . '" was deleted';
			return $this->checkTextLen($text);
	    }

	    return '';
	}

	public function getData(){

		$attrs = [];
		foreach ($this->attributes as $key => $value) {
			if(! in_array($key, ['created_at','updated_at'])) {
				$attrs[$key] = $value;
			}
		}

		$attrsJson = json_encode( $attrs );

		return $attrsJson;
	}

	public function checkTextLen($text) {
		if(strlen($text) > 254) {
			$text = substr($text,0,254);
		}

		return $text;
	}
	
}
