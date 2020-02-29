<?php

namespace App\Api\V1\Controllers;

use Config;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Guzzle\Http\Exception\BadResponseException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use App\Sequence;
use App\Api\V1\Traits\LoggerHelper;
use GuzzleHttp\Client;
use App\Mastermodel;
use App\Api\V1\Helper\Node;
use App\Board;
use App\ColumnSetting;
use App\Doc_to;
use App\Http\Controllers\QaController;
use App\Quality;
use App\LineprocessManualInstruction;
use DB;
class TestController extends Controller
{	
	use LoggerHelper;

	// the controller should always have allowedParameter
	protected $allowedParameter = [
		'sequence_id', 'name', 'mac_address','ip_address'
	];

	public function __construct(){
		$this->model = new Sequence;
	}

	// $action=null, $desc = null, $scannerId=null 
	public function index(Request $request){

		$c = new QaController;
		$request->modelname = 'DDXGT500RA9N';
		$request->scanner_id = 80;
		$request->lotno = '090A';

		// return $request;
		return $c->getFinishCount($request);
		
		$results = (new Doc_to)->getLotSize('DDXGT500RA9N', '090A');

		return $results;

	}

	public function testNode(){
		return 'wawa';
	}

	public function store(Request $request){
		/* test method getData dari modelBased */
		$manualInstruction = (new LineprocessManualInstruction());
		$manualInstruction->name = 'rangga';
		$manualInstruction->kelas = 9;
		// dd($manualInstruction);
		return $manualInstruction->getData();
		
		$node = new Node($request->all());

		return [
			'fifoMode' => $node->checkFifo()
		];

		return [
			'get_serial_number' => $node->getSerialNumber(),
			'is_serialnumber_mandatory' => $node->isSerialMandatory(),
			'rollbackMaster' => $node->rollbackMaster()
		];
		
		return [
			'check_instruction_manual' => setting('admin.check_instruction_manual'),
			'checkInstructionManual' => $node->checkInstructionManual(),
			'test' => setting('admin.check_instruction_manual') && $node->checkInstructionManual(),
			'hasInstructionManual' => $node->hasInstructionManual(),
			'node' => $node
		];

	}

	public function testGuzzle(Request $request){
		$client = new Client();
		// $url = 'http://localhost:80/mapros_system54/public/api/aoies';
		// $url = 'http://136.198.117.48/mapros_system54/public/api/aoies';
		$url = "http://136.198.117.48/mecha/api/inspects";
        $res = $client->get($url, [	
    		'query' => [
    			'board_id'	=> $request->board_id,
    		],
	 		'headers' => ['Content-type' => 'application/json'],
        ]);
        $result = json_decode( $res->getBody(), true );

        return $result;
	}
}
