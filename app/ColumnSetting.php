<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class ColumnSetting extends Model
{
    protected $table='column_settings';

    /* public function getActivityDescriptionForEvent($eventName)
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
			'dummy_column' => $this->dummy_column,
			'name' => $this->name,
			'table_name' => $this->table_name,
			'code_prefix' => $this->code_prefix
		]);
	} */
}
