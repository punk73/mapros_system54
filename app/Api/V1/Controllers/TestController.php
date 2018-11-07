<?php

namespace App\Api\V1\Controllers;

use Config;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use App\Sequence;
use App\Api\V1\Traits\LoggerHelper;
use GuzzleHttp\Client;
use App\Mastermodel;
use App\Api\V1\Helper\Node;

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
		return 'hai';
	}

	public function testNode(){
		return 'wawa';
	}

	public function store(Request $request){
		$node = new Node($request->all());
		return [
			'node' => $node,
			'isRunOut' => $node->isRunOut(8)
		];
		// return $node->isSettingContainChildrenOf('ticket');
		// return ($node->isExists()) ? 'true' : 'false' ;
		// return ($node->hasChildren()) ? 'true' : 'false' ;

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
