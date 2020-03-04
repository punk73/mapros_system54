<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;
use DB;
class SerialNo extends Model
{
    // connect ke db OCS
    public function __construct(){
        $this->connection = env('DB_CONNECTION_FB', 'firebird');
    }

    protected $table = 'SERIAL_NO';
	
	public $timestamps = false;

	public $incrementing = false;

    protected $primaryKey = 'ID_SERIAL_NO'; // or null
    
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
    
}
