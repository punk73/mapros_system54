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
use App\Quality;
use App\LineprocessManualInstruction;
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
		/* $client = new Client;
		try {
			$client->get('http://google.com/nosuchpage');    
		}
		catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			return $responseBodyAsString;
		}

		return $client; */

		/* $url = 'http://localhost/mapros_system54/public/api/aoies';
		$client = new Client();
		// $url = "https://api.github.com/repos/guzzle/guzzle";
		try {
			$res = $client->get($url, [	
				'query' => [
					'board_id'	=> $request->board_id
				],
				'headers' => ['Content-type' => 'application/json'],
				// 'http_errors' => false,
			]);
		} catch ( \GuzzleHttp\Exception\ClientException  $e) {
			// return $e->getMessage();
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			throw new StoreResourceFailedException('something went wrong', []);
		} */
		/* $data = Quality::select([
			'ID_QUALITY'
			,'MODEL'
			,'BOARD'
			,'PCB_ID_NEW'
			,'PCB_ID_OLD'
			,'GUIDMASTER'
			,'APPROVED'
		])->where('GUIDMASTER', '!=', null )
			->where('PCB_ID_OLD', '!=', "-")
			->where(function($query){
				return $query->where('APPROVED', '=', NULL )
				  ->orWhere('APPROVED', 0);
			})
			->orderBy('ID_QUALITY','desc')
			->paginate();

		return $data; */
		
		$tmp = 'G1P9';
		$results = [];
		foreach(range('B', 'Z') as $key => $value) {
			$temp = [
				"name" => "lcd",
				"dummy_column" => "barcode",
				"table_name" => "parts",
				"code_prefix" => $tmp . $value,
				"level" => 3
			];

			$results[] = ColumnSetting::firstOrCreate($temp);
		}

		// return ColumnSetting::take(10)->get();

		/* 
			"name": "lcd",
			"dummy_column": "barcode",
			"table_name": "parts",
			"code_prefix": "G1P00",
		*/

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
