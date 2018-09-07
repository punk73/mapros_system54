<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\V1\Requests\IpRequest;
use App\Scanner;
use Dingo\Api\Exception\StoreResourceFailedException;

class ConfigController extends Controller
{
    public function index(IpRequest $request){
    	$scanner = Scanner::select([
    		'lines.name as line',
    		'lineprocesses.name as process',
    		'lineprocesses.type',
            'lineprocesses.id as lineprocess_id',
    	])->with(['lineprocess.columnSettings' => function ($query){
            $query->select();
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

    	return [
    		'success' => true,
    		'data' => $scanner
    	];
    }
}
