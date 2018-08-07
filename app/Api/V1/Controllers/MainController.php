<?php

namespace App\Api\V1\Controllers;

use Config;
use App\Board;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\BoardRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use App\Api\V1\Traits\LoggerHelper;
use App\Api\V1\Helper\Node;
use Dingo\Api\Exception\StoreResourceFailedException;

class MainController extends Controller
{
    use LoggerHelper;

    protected $allowedParameter = [
        'board_id',
        'nik',
        'ip',
        'is_solder'
    ];

    private function getParameter (BoardRequest $request){
        $result = $request->only($this->allowedParameter);

        // setup default value for ip 
        $result['ip'] = (!isset($result['ip'] )) ? $request->ip() : $request->ip ;
        // setup default value for is_solder is false;
        $result['is_solder'] = (!isset($result['is_solder'] )) ? false : $request->is_solder ;

        return $result;
    }

    public function store(BoardRequest $request ){
    	$parameter = $this->getParameter($request);
        // cek apakah board id atau ticket;
        $node = new Node($parameter);

        // cek current is null;
        if(!$node->isExists()){
            // cek kondisi sebelumnya is null
            if (!$node->prev()->isExists()){
                // ga ada,
                throw new Exception("Error Processing Request", 1);
            };
                  

            
        }
        // if is_solder == true, maka cek data di table boards based on boad id & scanner_id

    }
    
    
}
