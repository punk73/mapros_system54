<?php

/**
 * 
 */
namespace App\Api\V1\Helper;
use App\Model;
use App\Board;
use App\Ticket;
use App\Scanner;
use App\Sequence;
use App\Mastermodel;
use App\Lineprocess;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class Node
{
	protected $model;

	protected $ticketCriteria = [
		'MST', 'PNL', 'MCH'
	];
	protected $allowedParameter = [
		'board_id',
        'nik',
        'ip',
        'is_solder'
	];

	public $scanner_id;
	public $scanner;
	public $dummy_id; //it could be ticket_no, board_id, ticket_no_master based on the model
	public $guid_master;
	public $guid_ticket;
	public $status;
	public $judge = 'OK';
	public $nik;
	public $board = [
		'name' => null,
		'pwbname' => null,
	];
	public $lineprocess;
	public $process;
	protected $dummy_column;

	function __construct($parameter){	
		// setup model
		$this->setModel($parameter);
		// setup scanner_id;
		$ip = $parameter['ip'];
		$this->setScannerId($ip);
		// setup nik
		$this->nik = $parameter['nik'];
		// setup board_id
		$this->dummy_id = $parameter['board_id'];

		$this->getBoardType();
		// run to get sequence and set to process attribute
		$this->getSequence();

		// set lineprocess
		$this->setLineprocess($this->scanner['lineprocess_id']);
	}

	public function __toString(){
		return json_encode([
			'scanner_id' 	=> $this->scanner_id,
			'scanner'		=> $this->scanner,
			'dummy_id' 		=> $this->dummy_id,
			'guid_master' 	=> $this->guid_master,
			'guid_ticket' 	=> $this->guid_ticket,
			'status' 		=> $this->status,
			'judge' 		=> $this->judge,
			'nik' 			=> $this->nik,
			'board'			=> $this->board,
			'process'		=> $this->process,
			'lineprocess'	=> $this->lineprocess
		]);
	}

	// automaticly triggered on instantiate
	public function setScannerId($scanner_ip){
		$scanner = (Scanner::where('ip_address', $scanner_ip )->exists()) ? Scanner::select([
			'id',
			'line_id',
			'lineprocess_id',
			'name',
			'mac_address',
			'ip_address',
		])->where('ip_address', $scanner_ip )->first() : null ;
		$this->scanner = $scanner;
		$this->scanner_id = $scanner['id'];
	}

	//triggered on instantiate 
	private function setModel($parameter){
		$code = substr($parameter['board_id'], 0, 3);
		// setup which model to work with
		if (in_array($code, $this->ticketCriteria )) {
			// it is ticket, so we work with ticket
			if($code == 'MST'){
				$this->model = new Master;
				$this->dummy_column = 'ticket_no_master';
			}else {
				$this->model = new Ticket;
				$this->dummy_column = 'ticket_no';
			}
		}else {
			// it is a board, we working with board;
			$this->model = new Board;
			$this->dummy_column = 'board_id';

		}
	}

	public function getModel(){
		return $this->model;
	}

	public function isExists(){
		if(is_null($this->lineprocess)){
			throw new StoreResourceFailedException("this lineprocess is not set", [
				'message' => $this->lineprocess
			]);	
		}

		if($this->lineprocess['type'] == 1 ){
			return (
				$this->model
				->where( 'scanner_id' , $this->scanner_id  )
				->where( $this->dummy_column, $this->dummy_id )
				->count() > 0 
			);
		}else{
			// send cURL here;
		}
	}
	
	// no longer use due to huge latency
	protected $big_url = 'http://136.198.117.48/big/public/api/models';
	// no longer use due to huge latency
	public function getBoardTypeCurl($board_id = null, $url=null){
		// what if board id morethan 5 character ??
		// what if board id null ??
		if (is_null( $board_id)) {
			$board_id = $this->dummy_id;
			// get first 5 digit of char
			$board_id = substr($board_id, 0, 5);
		}

		// default value of url is $this->big_url, it is for testing purposes
		if (is_null( $url)) {
			$url = $this->big_url;
		}

		$parameter = '?code=' . $board_id;
		// init curl
		$curl = curl_init();

		if($curl == false){
            throw new HttpException(422);
        }
		// set opt
		curl_setopt_array($curl, [
		    CURLOPT_URL => $url . $parameter,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_TIMEOUT => 30000,
		    CURLOPT_HTTPGET => true,
		    CURLOPT_HEADER => 0,
		    CURLOPT_HTTPHEADER => array(
		    	// Set Here Your Requesred Headers
		        'Content-Type: application/json',
		    ),
		]);

		// send curl
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		// what if error ??
		if ($err) {
			throw new HttpException(422);
		}
		// decode json text into associative array;
		$result = json_decode($response, true);

		// what if not found ??
		if (count($result['data']) > 0) {
			return $result['data'][0]['pwbname'];
		}else{
			throw new HttpException(422);	
		}
	}

	public function getBoardType($board_id = null){
		if (is_null($board_id)) {
			$board_id = $this->dummy_id;
			// get first 5 digit of char

			// it'll need to be changed due to changes in big system
			$board_id = substr($board_id, 0, 5);
		}

		$model = Mastermodel::select([
			'name',
			'pwbname',
		])->where('code', $board_id )
		->first();

		if ($model) {
			$this->setBoard($model);
		}

		return $this;

	}

	public function setBoard($model=['name'=>null, 'pwbname'=>null ]){
		$this->board['name'] = $model['name'];
		$this->board['pwbname'] = $model['pwbname'];
	}

	public function getBoard(){
		return $this->board;
	}

	public function getScanner(){
		return $this->scanner;
	}

	// it used to set process
	public function getSequence(){
		$board   = $this->getBoard();
		$scanner = $this->getScanner();


		if (!is_null($board['name'])) {
			# code...

			$sequence = Sequence::select(['process'])->where('modelname', $board['name'] )
			->where('pwbname', $board['pwbname'])
			->where('line_id', $scanner['line_id'] )
			->first();

			if($sequence){
				$this->setProcess($sequence['process']);
			}
		}

		return $this;

	}

	public function setProcess($process){
		$this->process = $process;
	}

	public function getProcess(){
		if (is_null( $this->process) ) {
			$this->getSequence();
		}
		return $this->process;
	}

	public function setLineprocess($lineprocess_id){

		// cek status internal atau external
		$lineprocess = Lineprocess::select([
			'id',
			'name',
			'type',
			'std_time',
			'endpoint_id',
		])->find($lineprocess_id);

		if(!$lineprocess->exists){
			throw new StoreResourceFailedException("lineprocess not found", [
                'current_step' 	=> $this->scanner['lineprocess_id'],
                'process'		=> $process,
            ]);			
		}

		$this->lineprocess = $lineprocess;
	}

	public function move($step = 1){
		if( is_null($this->process) ){
			throw new StoreResourceFailedException("Process Not found", [
                'message' => 'Process not found'
            ]);
		}

		if(is_null($this->scanner)){
			throw new StoreResourceFailedException("scanner not registered yet", [
                'message' => 'scanner not registered yet'
            ]);
		}

		// set process into array
		$process = explode(',', $this->process);
		
		// get current process index;
		$key = array_search($this->scanner['lineprocess_id'], $process );
		
		// $lineprocess_id tidak ditemukan di $process
		if (!$key) {
			throw new StoreResourceFailedException("this step shouldn't belong to the process", [
                'current_step' 	=> $this->scanner['lineprocess_id'],
                'process'		=> $process,
            ]);	
		}

		// kalau 0, maka ga ada prev;
		if(!$key == 0 ){
			$newIndex = $key + $step;
			// cek new index key ada di array $process as key. prevent index not found error 
			if(array_key_exists($newIndex, $process )){
			
				$newLineProcessId = $process[$newIndex];

				// setup lineprocess;
				$this->setLineprocess($newLineProcessId);

				$scanner = Scanner::select([
					'id',
					'line_id',
					'lineprocess_id',
					'name',
					'mac_address',
					'ip_address',
				])->where('lineprocess_id', $newLineProcessId )
				->first();
	
				if(!$scanner){ //kalau scanner tidak ketemu
					throw new StoreResourceFailedException("scanner not registered yet", [
		                'message' => 'scanner not registered yet'
		            ]);
				}
				// setup new scanner id value;
				$this->scanner_id = $scanner['id'];
				$this->scanner = $scanner;
			}

		}

		return $this;

	}

	public function prev(){
		return $this->move(-1);
	}

	public function next(){
		return $this->move(1);
	}
}