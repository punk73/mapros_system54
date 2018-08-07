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

    protected $returnValue = [
        'success' => true,
        'message' => 'data saved!'
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
        if(!$node->isExists()){ //board null
            // cek kondisi sebelumnya is null
            $prevNode = $node->prev();
            if( $prevNode->getStatus() == 'OUT' ){
                
                // we not sure if it calling prev() twice or not, hopefully it's not;
                if($prevNode->getJudge() !== 'NG'){
                    // cek repair 
                    // $prevNode->existsInRepair(); //not implement yet
                    /*if($prevNode->existsInRepair()){

                    }*/
                    $node = $prevNode->next();
                    $node->setStatus('IN');
                    $node->setJudge('OK');
                    if(!$node->save()){
                        return $this->returnValue;
                    };
                }
            }

            if( $prevNode->getStatus() == 'IN' ){
                // error handler
                if($prevNode->getModelType() !== 'board'){
                    throw new StoreResourceFailedException("DATA NOT SCAN OUT YET!", [
                        'message' => '',
                        'note' => $prevNode
                    ]);
                }

                // cek apakah solder atau bukan
                if (!$parameter['is_solder']) { //jika solder tidak diceklis, maka
                    throw new StoreResourceFailedException("DATA NOT SCAN OUT YET!", [
                        'message' => '',
                        'note' => $prevNode
                    ]);    
                }
                
                if($prevNode->isExists()){
                    throw new StoreResourceFailedException("DATA ALREADY SCAN OUT!", [
                        'message' => '',
                        'note' => $prevNode
                    ]);    
                };

                $node = $prevNode->next();
                $node->setStatus('OUT');
                $node->setJudge('SOLDER');
                if(!$node->save()){
                    return $this->returnValue;
                };
            }

            // jika get status bukan in atau out maka throw error
            throw new StoreResourceFailedException("DATA NOT SCAN IN PREVIOUS STEP", [
                'node' => json_decode( $prevNode, true )
            ]);
            
        }

        // disini node sudah exists
        if($node->getStatus() == 'OUT'){
            if($parameter['is_solder'] == false){
                throw new StoreResourceFailedException("DATA ALREADY SCAN OUT!", [
                    'node' => json_decode( $prevNode, true )
                ]);    
            }

            //isExists already implement is solder, so we dont need to check it again.
            //if the code goes here, we save to immediately save the node;

            $node->setStatus('OK');
            $node->setJudge('SOLDER');
            if(!$node->save()){
                return $this->returnValue;
            } 
        }

        if($node->getStatus() == 'IN'){
            // cek $node->
            $currentStep = $node->getStep();
            // we need to count how long it is between now and step->created_at
            
            //if it > $currentStep['std_time'], then save it; 
            // $currentStep['created_at'] 

        }
        

    }
    
    
}
