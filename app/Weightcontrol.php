<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Firebird\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;
use function GuzzleHttp\json_encode;

class Weightcontrol extends Model implements LogsActivityInterface
{
	use LogsActivity;

	protected $table = 'WEIGHTCONTROL';
	
	public $timestamps = false;

	public $incrementing = false;

	protected $primaryKey = 'ID'; // or null

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
	        return $this->table .' "'. $this->getData() . '" was deleted';
	    }

	    return '';
	}

	public function getData() {
		return json_encode([
			'MODEL_NAME' => $this->MODEL_NAME,
			'STDWEIGHT' => $this->STDWEIGHT,
			'TOLERANCE' => $this->TOLERANCE,
			'SCALE' => $this->SCALE,			
		]);
	}


}
