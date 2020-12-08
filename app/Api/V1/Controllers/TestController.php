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
use App\SerialNo;
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
	public function index(Request $request) {

		$c = new QaController;
		return $c->getMainQuery($request);

		$node = new Node(null, true);
		$guid = "95A25DE7-EF5C-4D6F-95D7-9B7A2ABCDE30";
		return $node->storeSerialNumberRework($guid);

	}

	public function getMainQuery(Request $request) {
		
		$serialNumbers = SerialNo::
			select(['SERIAL_NO_ID'])
			->where('MODEL_NAME', $request->modelname )
			->where('PROD_NO', $request->lotno)
			->distinct()
			->get();
		
		$serialno = [];
		foreach($serialNumbers as $sn) {
			$serialno[] = trim( $sn->SERIAL_NO_ID);
		}

		$data =  DB::connection('mysql3')
            ->table('masters')
            ->select([
                DB::raw('right(serial_no,8) as serial_no')
                , 'judge'
                , 'scan_nik'
                , 'created_at'
			])
				->whereIn('serial_no', $serialno )
				->where('scanner_id', $request->scanner_id )
				->where('judge', 'OK')
			->groupBy('serial_no')
			->distinct()
			->get();

		return [
			'count' => count( $data),
			'data' => $data,
		];
	}

	public function testNode(){
		return 'wawa';
	}

	public function store(Request $request){

		$node = new Node($request->all());

		return [
			'checkInstructionManual' => $node->checkInstructionManual(),
			'check_instruction_manual' => setting('admin.check_instruction_manual'),
			'hasInstructionManual' => $node->hasInstructionManual()
		];

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
