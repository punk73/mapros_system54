<?php

namespace App;

use Firebird\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;
use function GuzzleHttp\json_encode;
use Activity;

class Quality extends Model
{
    use LogsActivity;

	protected $table = 'QUALITY';
	
	public $timestamps = false;

	public $incrementing = false;

	protected $primaryKey = 'ID_QUALITY'; // or null

	protected $fillable = [
    	'ID_QUALITY' 
        ,'MODEL'     
        ,'BOARD'     
        ,'PCB_ID_NEW'
        ,'PCB_ID_OLD'
        ,'GUIDMASTER'
        ,'APPROVED'  
    ];

    public function __construct(){
      $this->connection = env('DB_CONNECTION_FB2', 'firebird2');
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
			'ID_QUALITY' => $this->ID_QUALITY,
            'MODEL' => $this->MODEL,
            'BOARD' => $this->BOARD,
            'PCB_ID_NEW' => $this->PCB_ID_NEW,
            'PCB_ID_OLD' => $this->PCB_ID_OLD,
            'GUIDMASTER' => $this->GUIDMASTER,
            'APPROVED' => $this->APPROVED,	
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
