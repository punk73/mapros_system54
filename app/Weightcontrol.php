<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Firebird\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;

class Weightcontrol extends Model
{
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
}
