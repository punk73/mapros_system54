<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Database\Query\FirebirdQueryBuilder as QueryBuilder;
use DB;
class Doc_to extends Model
{
    // connect ke db OCS
    public function __construct(){
        $this->connection = env('DB_CONNECTION_FB', 'firebird');
    }

    protected $table = 'DOC_TO';
	
	public $timestamps = false;

	public $incrementing = false;

    protected $primaryKey = 'ID_DOC_TO'; // or null
    
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
    
    public function getLotSize($model, $lotno) {
        $res = $this
            ->where('MODEL_NAME', strtoupper($model) )
            ->where('PROD_NO', strtoupper($lotno))
            ->sum(DB::raw('cast(LOT_SIZE_STR as int)'));

        return $res;
    }

	
}
