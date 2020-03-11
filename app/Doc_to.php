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
            ->select(['LOT_SIZE_IN'])
            ->orderBy('ID_DOC_TO', 'desc')
            ->first();
            
        if(!$res){
            return 0;
        }

        return $res->LOT_SIZE_IN;
        
        /* $res = $this
            ->where('MODEL_NAME', strtoupper($model) )
            ->where('PROD_NO', strtoupper($lotno))
            ->from(DB::raw("(
                SELECT DISTINCT MODEL_NAME
                , PROD_NO
                , START_SERIAL
                , cast(LOT_SIZE_STR as int) AS LOT_SIZE_STR
                FROM DOC_TO
            ) r"))
            ->sum('LOT_SIZE_STR');
            
        return $res; */

        /* $modelname = \strtoupper($model);
        $lot = \strtoupper($lotno);

        $res = DB::connection('firebird')->select("
            SELECT  MODEL_NAME
                , PROD_NO
                , SUM( LOT_SIZE_STR ) AS LOT_SIZE
            FROM (
                SELECT DISTINCT MODEL_NAME
                , PROD_NO
                , START_SERIAL
                , cast(LOT_SIZE_STR as int) AS LOT_SIZE_STR
                FROM DOC_TO
            ) r
            WHERE PROD_NO = '{$lot}' AND MODEL_NAME = '{$modelname}'
            GROUP BY MODEL_NAME, PROD_NO
        ");



        if(count($res) > 0) {
            return $res[0]->LOT_SIZE;
        }else {
            return 0;
        } */

    }

	
}
