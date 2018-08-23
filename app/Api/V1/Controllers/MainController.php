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
use Carbon\Carbon;

class MainController extends Controller
{
    use LoggerHelper;

    protected $allowedParameter = [
        'board_id',
        'nik',
        'ip',
        'guid',
        'is_solder',
        'modelname',
    ];

    protected $returnValue = [
        'success' => true,
        'message' => 'data saved!',
        'node'    => null  
    ];

    private function getParameter (BoardRequest $request){
        $result = $request->only($this->allowedParameter);

        // setup default value for ip 
        $result['ip'] = (!isset($result['ip'] )) ? $request->ip() : $request->ip ;
        // setup default value for is_solder is false;
        $result['is_solder'] = (!isset($result['is_solder'] )) ? false : $request->is_solder ;

        return $result;
    }

    /*
    *
    * $currentStep must contains created_at && std_time
    *
    */
    private function isMoreThanStdTime($currentStep){
        $now = Carbon::now();
        $lastScanned = Carbon::parse($currentStep['created_at']);

        // it'll return true if timeDiff is greater than std_time;
        return ( $now->diffInSeconds($lastScanned) > $currentStep['std_time'] );
    }

    public function store(BoardRequest $request ){
    	$parameter = $this->getParameter($request);
        // cek apakah board id atau ticket;
        $node = new Node($parameter);
        
        // return $node->getGuidTicket();

        if ($node->getModelType() == 'board') {
            return $this->processBoard($node);
        }

        /*if($node->getModelType() == 'board'){
            return 'critical';
        }*/ 
        if($node->getModelType() == 'ticket'){
            return $this->runProcedureTicket($node);
        }

        if($node->getModelType() == 'master'){
            return $this->runProcedureMaster($node);
        }

    }

    private function processBoard(Node $node){
        // cek current is null;
        /*return [
            'node' => $node->next(),
            'exists' => $node->isExists(),
            'isFirstSequence' => $node->isFirstSequence(),
            'scanner_id' => $node->scanner_id,
            'board_id'  => $node->dummy_id
        ];*/

        if(!$node->isExists()){ //board null
            // cek kondisi sebelumnya is null
            // kalau sequence pertama, maka insert; gausah cek data sebelumnya dulu;
            if ($node->isFirstSequence() ) {
                // langsung input;
                $node->setStatus('IN');
                $node->setJudge('OK');
                if(!$node->save()){
                    throw new StoreResourceFailedException("Error Saving Progress", [
                        'message' => 'something went wrong with save method on model! ask your IT member'
                    ]);
                };

                $this->returnValue['node'] = $node;

                return $this->returnValue;
            }

            $prevNode = $node->prev();

            if( $prevNode->getStatus() == 'OUT' ){
                
                // we not sure if it calling prev() twice or not, hopefully it's not;
                if($prevNode->getJudge() == 'NG'){                    
                    // kalau dia NG
                    // cek di table repair, ada engga datanya.
                    if( !$prevNode->isRepaired()){ //kalau ga ada, masuk sini
                        // kalau ga ada, maka throw error data is NG in prev stages! repair it first!
                        throw new StoreResourceFailedException("Data is error in previous step, repair it first!", [
                            'prevnode' => $prevNode,
                            'node'     => $prevNode->next() 
                        ]);
                    }
                }

                $node = $prevNode->next();
                $node->setStatus('IN');
                $node->setJudge('OK');
                if(!$node->save()){
                    throw new StoreResourceFailedException("Error Saving Progress", [
                        'message' => 'something went wrong with save method on model! ask your IT member'
                    ]);
                };
                $this->returnValue['node'] = $node;
                return $this->returnValue;
            }

            if( $prevNode->getStatus() == 'IN' ){
                // error handler
                if($prevNode->getModelType() !== 'board'){
                    throw new StoreResourceFailedException("DATA NOT SCAN OUT YET!", [
                        'message' => 'bukan board',
                        'note' => json_decode( $prevNode, true )
                    ]);
                }

                /*
                * cek logic below, I think we don't record the is solder in db;
                * it's mean it will always return false;
                */

                // cek apakah solder atau bukan
                if (!$prevNode->is_solder) { //jika solder tidak diceklis, maka
                    throw new StoreResourceFailedException("DATA NOT SCAN OUT YET!", [
                        'message' => 'bukan solder',
                        'node' => json_decode( $prevNode, true )
                    ]);    
                }
                
                if($prevNode->isExists()){
                    throw new StoreResourceFailedException("DATA ALREADY SCAN OUT!", [
                        'message' => '',
                        'note' => json_decode( $prevNode, true )
                    ]);    
                };

                $node = $prevNode->next();
                $node->setStatus('OUT');
                $node->setJudge('SOLDER');
                if(!$node->save()){
                    throw new StoreResourceFailedException("Error Saving Progress", [
                        'message' => 'something went wrong with save method on model! ask your IT member'
                    ]);
                    
                };
                $this->returnValue['node'] = $node;
                return $this->returnValue;
            }

            // jika get status bukan in atau out maka throw error
            throw new StoreResourceFailedException("DATA NOT SCAN IN PREVIOUS STEP", [
                'node' => json_decode( $prevNode, true )
            ]);
        }

        // disini node sudah exists
        if($node->getStatus() == 'OUT'){
            if($node->is_solder == false){
                throw new StoreResourceFailedException("DATA ALREADY SCAN OUT!", [
                    'node' => json_decode( $node, true )
                ]);    
            }

            //isExists already implement is solder, so we dont need to check it again.
            //if the code goes here, we save to immediately save the node;

            $node->setStatus('IN');
            $node->setJudge('SOLDER');
            if(!$node->save()){
                throw new StoreResourceFailedException("Error Saving Progress", [
                    'message' => 'something went wrong with save method on model! ask your IT member'
                ]);
            } 
            $this->returnValue['node'] = $node;
            return $this->returnValue;
        }

        // return $node->getStatus();
        if($node->getStatus() == 'IN'){

            $currentStep = $node->getStep();
            if($node->is_solder){
                throw new StoreResourceFailedException("DATA ALREADY SCAN IN!", [
                    'message' => 'you already scan solder with this scanner!'
                ]);

            }

            // we need to count how long it is between now and step->created_at
            if( !$this->isMoreThanStdTime($currentStep)){
                // belum mencapai std time
                throw new StoreResourceFailedException("DATA ALREADY Scan IN", [
                    'message' => 'you scan within std time '. $currentStep['std_time']. ' try it again later'
                ]);
            }
            
            // save
            $node->setStatus('OUT');
            $node->setJudge('OK');
            if(!$node->save()){
                throw new StoreResourceFailedException("Error Saving Progress", [
                    'message' => 'something went wrong with save method on model! ask your IT member'
                ]);
            } 
            $this->returnValue['node'] = $node;
            return $this->returnValue;
        }
    }

    private function runProcedureTicket(Node $node){
        if( (!$node->isGuidGenerated()) && ($node->isJoin()) ){

            $node->setStatus('IN');
            $node->setJudge('OK');
            if(!$node->save()){
                throw new StoreResourceFailedException("Error Saving Progress", [
                    'message' => 'something went wrong with save method on model! ask your IT member'
                ]);
            }    

            throw new StoreResourceFailedException("view", [
                'nik' => $node->getNik(),
                'ip' => $node->getScanner()['ip_address'],
                'dummy_id' => $node->dummy_id, 
                'guid'=>    $node->generateGuid(),
            ]);
        };

        return $this->processBoard($node);
        
    }

    private function runProcedureMaster(Node $node){
        $this->runProcedureTicket($node);

        $node->updateChildGUidMaster();
        $this->returnValue['node'] = $node;
        return $this->returnValue;
    }
    
    
}
