<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\V1\Requests\IpRequest;
use App\Scanner;
use Dingo\Api\Exception\StoreResourceFailedException;
use  App\Sequence;

class ConfigController extends Controller
{
    public function index(IpRequest $request){
    	$scanner = Scanner::select([
    		'lines.name as line',
    		'lineprocesses.name as process',
    		'lineprocesses.type',
			'lineprocesses.id as lineprocess_id',
			'lines.id as line_id'
            // 'lineprocesses.join_qty',
    	])->with(['lineprocess' => function ($lineprocess){
            $lineprocess->select(['id','name', 'join_qty'])
                ->with(['columnSettings' => function ($columnSettings){
                    $columnSettings->select(['name', 'table_name']);
                }]);
        }])
    	->where('ip_address', $request->ip )
    	->leftJoin('lines', 'scanners.line_id', '=', 'lines.id')
    	->leftJoin('lineprocesses', 'scanners.lineprocess_id', '=', 'lineprocesses.id')
    	->first();
    	if(is_null( $scanner)){
    		throw new StoreResourceFailedException("Scanner with ip ".$request->ip." not found", [
    			'ip' => $request->ip
    		]);
		};
		
		
		$scannerArray = $scanner->toArray();
		$sequence = null;
		if($request->has('modelname')){

			$isContainMaster = $this->isContainMasters($scannerArray);

			if($isContainMaster){
				$sequence = Sequence::select(['process'])
					->where('modelname', $request->modelname )
					->where('line_id', $scanner['line_id'] )
					->where('pwbname', 'master' )
					->first();
			}
		}

		$showSerialNumberField = false;
		if(!is_null($sequence)) {

			$process = explode(',', $sequence->process);

			// get current process index;
			$showSerialNumberField = $process[ count($process) - 1 ] == $scanner['lineprocess_id'];

		}


    	return [
			'success' => true,
			'sequence' => $sequence,
			'show_serial_number_field' => $showSerialNumberField,
    		'data' => $scanner
    	];
	}
	
	/* return boolean */
	public function isContainMasters($data) {
		$masterTable = 'masters';

		if(!isset($data['lineprocess'])) {
			return false;
		}

		if(!isset($data['lineprocess']['column_settings'])) {
			return false;
		}

		foreach ($data['lineprocess']['column_settings'] as $key => $columnSetting) {
			# code...
			if($columnSetting['table_name'] == $masterTable ) {
				return true;
			}
		}

		return false;
	}
}
