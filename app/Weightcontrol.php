<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Firebird\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;
use function GuzzleHttp\json_encode;
use Activity;

class Weightcontrol extends Model implements LogsActivityInterface
{
	// use LogsActivity;

	protected $table = 'WEIGHTCONTROL';
	
	public $timestamps = false;

	public $incrementing = false;

	protected $primaryKey = 'ID'; // or null

	protected $fillable = [
    	'MODEL_NAME',
		'STDWEIGHT',
		'TOLERANCE',
		'SCALE',
    ];

    public function __construct(){
      $this->connection = env('DB_CONNECTION_FB', 'firebird');
    }

    /**
	 * Get a new query builder instance for the connection.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function newBaseQueryBuilder() {
		$connection = $this->getConnection();

		return new QueryBuilder(
			$connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
		);
	}

	public function getActivityDescriptionForEvent($eventName)
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
	        return $this->table .' "'. $this->getData() . '" deleted';
	    }

	    return '';
	}

	public function getData() {
		return json_encode([
			'MODEL_NAME' => trim($this->MODEL_NAME),
			'STDWEIGHT' => trim( $this->STDWEIGHT),
			'TOLERANCE' => trim( $this->TOLERANCE),
			'SCALE' => trim( $this->SCALE),			
		]);
	}

	public function save(array $options = [] ) {
		$event = 'created';
		
		if ($this->exists) {
            $event = $this->isDirty() ? 'updated' : 'created';
		}
		
		Activity::log($this->getActivityDescriptionForEvent($event));
		// insert log here;
		return parent::save($options);
	}

	public function delete() {
		Activity::log($this->getActivityDescriptionForEvent('deleted'));

		return parent::delete();
	}


}
