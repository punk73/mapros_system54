<?php

/**
 * 
 */
namespace App\Api\V1\Helper;
use App\Model;
use App\Board;
use App\Ticket;
use App\Critical;
use App\Scanner;
use App\Sequence;
use App\Mastermodel;
use App\Repair;
use App\Lineprocess;
use App\ColumnSetting;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Guid;
use GuzzleHttp\Client;
use App\Endpoint;

class Node
{
	protected $model;
	protected $allowedStatus = [
		'IN',
		'OUT'
	];
	protected $ticketCriteria = [
		'__MST', '__PNL', '__MCH'
	];
	protected $allowedParameter = [
		'board_id',
        'nik',
        'ip',
        'is_solder',
        'guid'
	];
	public $scanner_id; //contain scanner id;
	public $scanner; //contain scanner object (App\Scanner)
	public $dummy_id; //it could be ticket_no, board_id, ticket_no_master based on the model
	public $guid_master;
	public $guid_ticket;
	public $status;
	public $judge = 'OK';
	public $nik;
	public $board;
	public $modelname;
	public $lotno;
	public $lineprocess; 
	public $is_solder; // is solder is flag to determine wheter it is solder process or not;
	public $process; // process is contain data from lineprocess.process exp : 1, 2, 3, 5, 4 dst
	protected $dummy_column; // it's board_id, ticket_no, or ticket_no_master based on model_type
	protected $model_type; // it's board, ticket, or master
	protected $id_type; //board, panel, master or mecha;
	protected $step; // step is contain data in table boads, tickets, or master based on modeltype
	protected $column_setting;
	protected $unique_column; // its contain board id, guid_ticket, or guid_master based model type
	protected $unique_id; // its contain board id, guid_ticket, or guid_master based model type
	protected $parameter;
	protected $key; //array index of sequence 
	// for conditional error view;
	protected $confirmation_view_error = 'confirmation-view';
	protected $firstSequence = false;

	function __construct($parameter, $debug = false ){	
		// kalau sedang debugging, maka gausah run construct
		if (!$debug)
		{	
			$this->parameter = $parameter;
			// setup model (board, ticket, or master)
			$this->setModel($parameter);
			// setup scanner_id;
			$ip = $parameter['ip'];
			$this->setScannerId($ip);
	
			// setup is_solder
			$this->is_solder = $parameter['is_solder'];
			// setup nik
			$this->nik = $parameter['nik'];
			// setup board_id
			$this->dummy_id = $parameter['board_id'];
			// get prev guid id;
			$guidId = (isset($parameter['guid'])) ? $parameter['guid'] : null;
			$this->initGuid($guidId);
			// get board type from big & set into board properties
			$this->getBoardType();
			// run to get sequence and set to process attribute
			$this->getSequence();
	
			// set lineprocess
			$this->setLineprocess($this->scanner['lineprocess_id']);
			// set column setting;
			$this->initColumnSetting();
			// set status & judge
			$this->loadStep();
			// set $key as current node positions
			$this->initCurrentPosition();
		}
	}

	public function __toString(){
		/*
		* for every properties that return here, need to be define earlier, not define in method,
		* so even when error occures, those attributes still exists as null;
		* Exp : you add $this->age properties in method, but doesn't declare those var in class, 
		* it'll show error without proper info what is the error; 
		*/
		return json_encode([
			'scanner_id' 	=> $this->scanner_id,
			'scanner'		=> $this->scanner,
			'id_type'		=> $this->id_type,
			'unique_column'	=> $this->unique_column,
			'unique_id'		=> $this->unique_id,
			'dummy_column'	=> $this->dummy_column,
			'dummy_id' 		=> $this->dummy_id,
			'guid_master' 	=> $this->guid_master,
			'guid_ticket' 	=> $this->guid_ticket,
			'status' 		=> $this->status,
			'judge' 		=> $this->judge,
			'nik' 			=> $this->nik,
			'board'			=> $this->board,
			'process'		=> $this->process,
			'lineprocess'	=> $this->lineprocess,
			'step'			=> $this->step,
			'model'			=> $this->model,
			'column_setting'=> $this->column_setting,
			// 'modelname'		=> $this->modelname,
			// 'lotno'			=> $this->lotno,
			'parameter'		=> $this->parameter,
		]);
	}

	public function getNik(){
		return $this->nik;
	}

	// automaticly triggered on instantiate
	public function setScannerId($scanner_ip){
		$scanner = Scanner::select([
			'id',
			'line_id',
			'lineprocess_id',
			'name',
			'mac_address',
			'ip_address',
		])->where('ip_address', $scanner_ip )->first();

		if (is_null($scanner)) {
			throw new StoreResourceFailedException("Scanner with ip=".$scanner_ip." not found. Perhaps scanner not registered yet", [
				'ip_address' => $scanner_ip,
				'message' => 'scanner not registered yet'
			]);
		}

		$this->scanner = $scanner;
		$this->scanner_id = $scanner['id'];
	}

	public function isJoin(){
		if ($this->column_setting == null ) {
			$this->column_setting = [];	
		}

		return (count($this->column_setting) > 1 );
	}

	public function initColumnSetting(){
		if ($this->lineprocess == null ) {
			throw new StoreResourceFailedException("Lineprocess is not found", [
				'node' => $this
			]);
		}

		$this->setColumnSetting( $this->lineprocess->columnSettings );

	}

	public function getColumnSetting(){
		return $this->column_setting;
	}

	public function setColumnSetting( $columnSetting){
		$this->column_setting = $columnSetting;
	}

	// dipanggil di setmodel
	public function setIdType($type){
		$this->id_type = $type;
	}
	// dipanggil di getSequence untuk determine ini id type apa;
	public function getIdType(){
		return $this->id_type;
	}

	//triggered on instantiate 
	public function setModel($parameter){
		if (count($parameter['board_id']) == 24 ) {
			$code = substr($parameter['board_id'], 0, 10);
		}else if(count($parameter['board_id']) <= 16){
			$code = substr($parameter['board_id'], 0, 5);
		}else{
			//ini untuk critical parts; gausah di substr dulu;
			$code = $parameter['board_id'];
		} 

		/*if (in_array($code, $this->ticketCriteria )) {
			// it is ticket, so we work with ticket
			if($code == '_MST'){
				$this->model = new Master;
				$this->dummy_column = 'ticket_no_master';
				$this->model_type = 'master';
			}else {
				$this->model = new Ticket;
				$this->dummy_column = 'ticket_no';
				$this->model_type = 'ticket';
			}
		}else {
			// it is a board, we working with board;
			$this->model = new Board;
			$this->dummy_column = 'board_id';
			$this->model_type = 'board';
		}*/

		$setting = ColumnSetting::where('code_prefix', $code )->first();
		if(!is_null($setting)){
			$className = 'App\\' . studly_case(str_singular($setting->table_name));

			if(class_exists($className)) {
			    $model = new $className;
			    $dummy_column = $setting->dummy_column;
			    $name = str_singular($setting->table_name);
			    $idType = $setting->name;
			    $unique_column = 'guid_' . $name; //guid_ticket or guid_master
			}

		}else{
			$setting = ColumnSetting::where('code_prefix', null )->first();
			// fwrite(STDOUT, print_r($setting));
			$model = new Board;
			$dummy_column = 'board_id';
			$name = 'board';
			$idType = 'board';
			$unique_column = 'board_id';
		}

		$this->model = $model;
		$this->dummy_column = $dummy_column;
		$this->model_type = $name;
		$this->unique_column = $unique_column;
		$this->setIdType($idType);
	}

	// method init guid di triggere dari main Controller;
	private function initGuid($guid){
		// it can triggered after scanner & model has been set; 
		if ($this->getModelType() == 'ticket') {
			
			// cek apakah ticket guid sudah di generate sebelumnya;
			if ($this->isGuidGenerated() ) {
				$guid = $this->getLastGuid();

				$guid = (!is_null($guid)) ? $guid['guid_ticket'] : null; 
			}else {

				$guid = ($guid == null )?  $this->generateGuid() : $guid;
			}

			$this->setGuidTicket($guid);
		}

		if($this->getModelType() == 'master'){

		}
		
		$this->setUniqueId($guid);
	}

	private function setUniqueId($guid){
		$this->unique_id = $guid;
	}

	public function getUniqueId(){
		return $this->unique_id;
	}

	private function getLastGuid(){
		if (is_null($this->dummy_column)) {
			throw new StoreResourceFailedException("dummy_column id is null", [
				'node' => json_decode($this, true),
			]);
		}

		if (is_null($this->dummy_id)) {
			throw new StoreResourceFailedException("dummy_id id is null", [
				'node' => json_decode($this, true),
			]);
		}

		return $this->model
		->select([
			'guid_ticket'
		])
		->where( $this->dummy_column, $this->dummy_id )
		->where('guid_master', null )
		->where('guid_ticket','!=', null )
		->orderBy('id', 'desc')
		->first();
	}

	public function getGuidTicket(){
		return $this->guid_ticket;
	}

	public function setGuidTicket($guid){
		$this->guid_ticket = $guid;
	}

	public function setGuidMaster($guid){
		$this->guid_master = $guid;
	}

	public function getGuidMaster(){
		return $this->guid_master;
	}

	public function isGuidGenerated(){
		if (is_null($this->model)) {
			throw new StoreResourceFailedException("node model is null", [
				'node' => json_decode($this, true ),
			]);
		}

		if (is_null($this->dummy_column)) {
			throw new StoreResourceFailedException("node dummy_column is null", [
				'node' => json_decode($this, true ),
			]);
		}

		if (is_null($this->dummy_id)) {
			throw new StoreResourceFailedException("node dummy_id is null", [
				'node' => json_decode($this, true ),
			]);
		}

		if($this->getModelType() == 'ticket'){
			return $this->model
				// ->where( 'scanner_id' , $this->scanner_id  )
				->where( $this->dummy_column, $this->dummy_id )
				->where('guid_master', null )
				->exists();
		}else if ($this->getModelType() == 'master'){
			return $this->model
				->where( $this->dummy_column, $this->dummy_id )
				->where('serial_no', null )
				->exists();
		}
	}

	public function generateGuid(){
		// cek apakah php punya com_create_guid
		if (function_exists('com_create_guid') === true){
	        $guid = trim(com_create_guid(), '{}');
	    }else{
    	    $guid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    	}

    	/*$newGuid = new Guid(['guid'=> $guid]);
        $newGuid->save();*/
        return $guid;
	
	}

	public function getModel(){
		return $this->model;
	}

	/*
	* isRepaired is function to check data in table repair;
	* the return value is boolean;
	*/
	public function isRepaired(){
		return Repair::where('unique_id', $this->unique_id )
		->exists();
	}

	public function getModelType(){
		return $this->model_type;
	}

	public function isExists($status=null, $judge=null, $is_solder = null ){
		if(is_null($this->lineprocess)){
			throw new StoreResourceFailedException("this lineprocess is not set", [
				'message' => $this->lineprocess
			]);	
		}

		if($this->lineprocess['type'] === 1 ){
			// masuk kesini jika internal;
			$model = $this->model
			->where( 'scanner_id' , $this->scanner_id  )
			->where( $this->dummy_column, $this->dummy_id );
			
			if (!is_null($status)) {
				$model = $model->where('status', 'like', $status.'%' );
			}

			if (!is_null($judge)) {
				$model = $model->where('judge', 'like', $judge.'%' );
			}

			// $is_solder is parameter, if it refer to $this->is_solder, it broke the logic in mainController;
			if(!is_null($is_solder)){
				$model = $model->where('judge', 'like', 'SOLDER%');
			}

			return $model->exists(); 

		}else if ($this->lineprocess['type'] == 2 ){
			// send cURL here;
			$endpoint = Endpoint::select()->find($this->lineprocess['endpoint_id']);
			if(is_null($endpoint)){
				throw new StoreResourceFailedException("endpoint with id ".$this->lineprocess['endpoint_id']." is not found", [
					'lineprocess' => $this->lineprocess,
				]);
			}

			$url = $endpoint->url; //'http://localhost/mapros_system54/public/api/aoies';
			$client = new Client();
			// $url = "https://api.github.com/repos/guzzle/guzzle";
	        $res = $client->get($url);
	        // it's should return boolean
	        return $res->status;
		}
	}

	public function isIn(){
		return $this->isExists('IN');
	}

	public function isOut(){
		return $this->isExists('OUT');
	}

	public function isOk(){
		return $this->isExists(null, 'OK');
	}

	public function isInOk(){
		return $this->isExists('IN', 'OK');
	}

	public function isOutOK(){
		return $this->isExists('OUT', 'OK');
	}

	public function save(){
		$model = $this->model;
		$model[$this->dummy_column] = $this->dummy_id;
		$model->guid_master = $this->guid_master;
		$model->guid_ticket = $this->guid_ticket;
		$model->scanner_id = $this->scanner_id;
		$model->status = $this->status;
		$model->judge = $this->judge;
		$model->scan_nik = $this->nik;

		if ($this->getModelType() == 'board' ) {
			$model->modelname = $this->modelname;
			$model->lotno = $this->lotno;	
		}

		return $model->save();
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

	/*
	* it's search board id type from table in big system 
	* based on code = board_id;
	*/
	public function getBoardType($board_id = null){
		// it's can be triggered if model & guid has been set;
		if (is_null($this->model)) {
			throw new StoreResourceFailedException("model is not found", [
				'node' => json_decode($this, true),
			]);
		}

		if (is_null($board_id)) {
			$board_id = $this->dummy_id;
			// get first 5 digit of char

			// it'll need to be changed due to changes in big system
			$board_id = substr($board_id, 0, 5);
		}

		$model = Mastermodel::select([
			'id',
			'name',
			'pwbno',
			'pwbname',
			'process',
			'cavity',
			'code',
			'side',
		]);

		if($this->getModelType() == 'ticket'){
			if (is_null($this->guid_ticket)) {
				throw new StoreResourceFailedException("guid ticket is null", [
					'node' => json_decode($this, true),
				]);
			}

			// kalau sudah generated, baru masuk sini;
			if ($this->isGuidGenerated()) {
				// ambil dulu modelnya dari table board, kemudian pass hasilnya kesini;
				$boardPanel = Board::select([
					'board_id'
				])->where('guid_ticket', $this->guid_ticket )
				->orderBy('id', 'desc')
				->first();

				if (is_null($boardPanel)) {
					throw new StoreResourceFailedException("board with guid_ticket ".$this->guid_ticket." not found", [
						'node' => json_decode($this, true ),
					]);
				}

				// this will cause some trouble later;
				$board_id = substr($boardPanel['board_id'], 0,5 );
				# code...
				$model = $model->where('code', $board_id );
			}/*else {
				// kalau belum, kita setup model based on user parameter;
				$model = $model->where('code', $this->parameter['modelname0'] );
			}*/
			
		}else{
			// this is from bigs db
			$model = $model->where('code', $board_id );
		}
			
		$model = $model->first();

		if ($model !== null) {
			$this->setBoard($model);
		}

		return $this;
	}

	public function setBoard($model = null){
		// $this->board['name'] = $model['name'];
		// $this->board['pwbname'] = $model['pwbname'];
		$this->board = $model;
	}

	public function getBoard(){
		return $this->board;
	}

	public function getScanner(){
		return $this->scanner;
	}

	/*
	* @loadStep is method to init current step;
	* step == current status & current judge;
	* we need to becarefull here since we had more than lineprocess type;
	*/
	public function loadStep(){

		$lineprocess = $this->getLineprocess();

		if(is_null($lineprocess)){
			throw new StoreResourceFailedException("Lineprocess is null", [
				'node' => $this,
			]);
		}

		if($lineprocess['type'] == 1) {//internal
	
			$model = $this->model
				->where( 'scanner_id' , $this->scanner_id  )
				->where( $this->dummy_column, $this->dummy_id )
				->orderBy('id', 'desc') //order menurun
				->first();

			if($model !== null){
				$this->setStatus($model->status );
				$this->setJudge($model->judge );
				$this->setStep($model);
			}
		}else {
			$this->procedureGetStepExternal();
		}

	}

	// called in loadStep
	public function procedureGetStepExternal(){
		// send ajax into end point;

		// end point should always contain status and judge;

	}

	// this method triggered by loadStep();
	private function setStep($model){
		$this->step = $model;
	}

	public function getStep(){
		return $this->step;
	}

	public function getJudge(){
		return $this->judge;
	}

	public function setJudge($judge){
		$this->judge = $judge;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		// make $status uppercase
		$status = strtoupper($status);

		if(!in_array($status, $this->allowedStatus )){
			throw new StoreResourceFailedException("Status ".$status. " not allowed ", [
				'allowed status' => $this->allowedStatus
			]);
			
		}

		$this->status = $status;
	}

	// it used to set process
	public function getSequence(){
		$board   = $this->getBoard();
		$scanner = $this->getScanner();


		if (!is_null($board['name'])) {
			// code below to avoid undefined error
			$this->parameter['modelname'] = (isset($this->parameter['modelname'])) ? $this->parameter['modelname'] : null;
			if($board['name'] != $this->parameter['modelname'] ){
				throw new StoreResourceFailedException($this->confirmation_view_error, [
					'node' => json_decode($this, true ),
					'server-modelname' => $this->board['name']
				]);
			}

			$sequence = Sequence::select(['process'])
			->where('modelname', $board['name'] )
			->where('line_id', $scanner['line_id'] );

			if ($this->getModelType() == 'board' ) {
				$sequence =	$sequence->where('pwbname', $board['pwbname']);
			}

			if ($this->getModelType() == 'ticket' ) {
				// disini kita harus determine wheter it is panel or mecha;
				$sequence =	$sequence->where('pwbname', $this->getIdType()  ); 
			}
			
			$sequence = $sequence->first();

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
		/*if (is_null( $this->process) ) {
			$this->getSequence();
		}*/
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

		if($lineprocess == null){
			throw new StoreResourceFailedException("lineprocess with id=".$lineprocess_id." not found", [
                'current_step' 	=> $this->scanner['lineprocess_id'],
                'process'		=> $this->process,
            ]);			
		}

		$this->lineprocess = $lineprocess;
	}

	public function getLineprocess(){
		return $this->lineprocess;
	}

	public function initCurrentPosition(){
		if( is_null($this->process) ){
			throw new StoreResourceFailedException("Process Not found", [
                'message' => 'Process not found',
                'node'	  => json_decode( $this, true ),
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
		$this->key = array_search($this->scanner['lineprocess_id'], $process );
		// $lineprocess_id tidak ditemukan di $process
		if ($this->key === false ) { // === is required since 0 is false if its using == (two sama dengan)
			throw new StoreResourceFailedException("this step shouldn't belong to the process", [
                'current_step' 	=> $this->scanner['lineprocess_id'],
                'process'		=> $process,
                'node'			=> json_decode($this,true) ,
            ]);	
		}
		
		$this->firstSequence = ($this->key === 0)? true:false;
	}

	public function move($step = 1){
		$process = explode(',', $this->process);

		// it's using $this->key for avoid error on first index;
		$this->key = $this->key + $step;
		// cek new index key ada di array $process as key. prevent index not found error 
		if(array_key_exists($this->key, $process )){
		
			$newLineProcessId = $process[$this->key];

			// setup $this->lineprocess to prev step;
			$this->setLineprocess($newLineProcessId);

			// will get the last scanner inputed by users
			$scanner = Scanner::select([
				'id',
				'line_id',
				'lineprocess_id',
				'name',
				'mac_address',
				'ip_address',
			])->where('lineprocess_id', $newLineProcessId )
			->where('line_id', $this->scanner['line_id'] )
			->orderBy('id', 'desc')
			->first();

			if(!$scanner){ //kalau scanner tidak ketemu
				throw new StoreResourceFailedException("scanner not registered yet", [
	                'message' => 'scanner not registered yet'
	            ]);
			}
			// setup new scanner id value;
			$this->scanner_id = $scanner['id'];
			$this->scanner = $scanner;

			// run load step to changes status & judge
			$this->loadStep();
		}

		// kalau 0, maka dia sequence pertama; we need to init key
		$this->initCurrentPosition();

		return $this;
	}

	/*
	* this is void, to update guid master of panel & board;
	* don't understand enough how to achieve it;
	*/
	public function updateChildGUidMaster(){
		// get guid master;
		// search panel, mecha, & board that has that guid master
		// get board that has same guid ticket 
	}

	public function isFirstSequence(){
		// default value of property below is false;
		// when move to prev and it's figure it out that is has key 0, (first index)
		// it'll setup to true;
		return $this->firstSequence;
	}

	public function prev(){
		return $this->move(-1);
	}

	public function next(){
		return $this->move(1);
	}
}