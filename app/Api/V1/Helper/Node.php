<?php

/**
 * 
 */
namespace App\Api\V1\Helper;
use App\Model;
use App\Board;
use App\Part;
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
use App\Symptom;
use App\Api\V1\Interfaces\ColumnSettingInterface;
use App\Api\V1\Interfaces\CriticalPartInterface;
use App\Api\V1\Interfaces\RepairableInterface;
use App\Api\V1\Traits\ColumnSettingTrait;
use App\Api\V1\Traits\CriticalPartTrait;
use App\Api\V1\Traits\RepairableTrait;

class Node implements ColumnSettingInterface, CriticalPartInterface, RepairableInterface
{
	use ColumnSettingTrait, CriticalPartTrait, RepairableTrait;

	protected $model; // App\Board, App\Master , App\Ticket, or App\Part;
	protected $model_code; // 5 char atau 11 char awal
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
	//protected $column_setting; //move to column setting trait
	protected $unique_column; // its contain board id, guid_ticket, or guid_master based model type
	protected $unique_id; // its contain board id, guid_ticket, or guid_master based model type for repair purposes
	protected $parameter;
	protected $key; //array index of sequence
	protected $symptom;
	protected $joinTimesLeft = 0; //jumlah sisa join, always update when hasChildren triggered
	// for conditional error view;
	protected $confirmation_view_error = 'confirmation-view';
	// protected $criticalParts; //dari CriticalPartTrait
	protected $firstSequence = false;

	function __construct($parameter, $debug = false ){
		// kalau sedang debugging, maka gausah run construct
		if (!$debug)
		{
			$this->parameter = $parameter;
			//get first 5 or 11 character of this dummy id / parameter['board_id']
			$this->initModelCode(); //dependence to $this->parameter;
			// setup model (board, ticket, or master)
			$this->setModel($parameter);
			// setup lot no
			$this->setLotno($parameter['board_id']);
			// setup scanner_id;
			$ip = $parameter['ip'];
			$this->setScannerId($ip);

			// setup is_solder
			$this->is_solder = $parameter['is_solder'];
			// setup nik
			$this->nik = $parameter['nik'];
			// setup board_id
			$this->dummy_id = $parameter['board_id'];

			// set lineprocess
			$this->setLineprocess($this->scanner['lineprocess_id']);
			// set column setting;
			// dependence to lineprocess;
			$this->initColumnSetting();

			// get prev guid id; this initGuid need to be called after initColumnSetting
			// dependence to initColumnSetting;
			$guidId = (isset($parameter['guid'])) ? $parameter['guid'] : null;
			$this->initGuid($guidId);

			// get board type from big & set into board properties
			// dependence to initGuid
			$this->getBoardType();
			// run to get sequence and set to process attribute
			// dependence to setScannerId, setModel, getBoardType()
			$this->getSequence();
			// set status & judge
			//dependence to setLineprocess;
			$this->loadStep();
			// set $key as current node positions
			$this->initCurrentPosition();
			// set the critical parts;
			$this->initCriticalPart();
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
			'modelname'		=> $this->modelname,
			'lotno'			=> $this->lotno,
			'critical_parts' => $this->criticalParts,
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
			throw new StoreResourceFailedException("Scanner dengan ip=".$scanner_ip." tidak ditemukan. Mungkin scanner belum di register oleh admin", [
				'ip_address' => $scanner_ip,
				'message' => 'scanner not registered yet'
			]);
		}

		$this->scanner = $scanner;
		$this->scanner_id = $scanner['id'];
	}

	public function initColumnSetting(){
		if ($this->lineprocess == null ) {
			throw new StoreResourceFailedException("Lineprocess Tidak Ditemukan", [
				'node' => $this
			]);
		}

		$this->setColumnSetting( $this->lineprocess->columnSettings );
	}

	public function initCriticalPart(){
		$critical = (isset($this->parameter['critical_parts'])) ? $this->parameter['critical_parts'] : null;
		if(!is_null($critical)){
			$this->setCriticalPart($critical);
		}
	}

	public function setDummyId($dummy_id){
		$this->dummy_id = $dummy_id;
	}

	// dipanggil di setmodel
	public function setIdType($type){
		$this->id_type = $type;
	}
	// dipanggil di getSequence untuk determine ini id type apa;
	public function getIdType(){
		return $this->id_type;
	}

	public function initModelCode(){
		if (strlen($this->parameter['board_id']) == 24 ) {
			$this->model_code = substr($this->parameter['board_id'], 0, 11 );
		}else if(strlen($this->parameter['board_id']) <= 23){ //ini 23 untuk akomodir mecha serial yg 24 character
			$this->model_code = substr($this->parameter['board_id'], 0, 5);
		}else{
			//ini untuk critical parts; gausah di substr dulu;
			$this->model_code = $this->parameter['board_id'];
		}
	}

	public function getModelCode(){
		return $this->model_code;
	}

	public function setModelCode($code){
		$this->model_code = $code;
	}
	//triggered on instantiate 
	public function setModel($parameter){
		

		$setting = $this->getColumnSettingWhereCodePrefix($this->model_code);
		// ColumnSetting::where('code_prefix', $this->model_code )->first();
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
			// $setting = ColumnSetting::where('code_prefix', null )->first();
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

	// method init guid di triggere dari constructor;
	private function initGuid($guidParam){
		// cek apakah ticket guid sudah di generate sebelumnya;
		if ($this->isGuidGenerated() ) {
			$guid = $this->getLastGuid(); //this method need update to acomodate master

			if($this->getModelType() == 'ticket'){
				// join dan column setting tidak contain board;
				if( $this->isJoin() && $this->isSettingContain('master') ){
					// cek apakah guid master sudah di generated based on ticket;
					// untuk cek guid master sudah generate atau belum dari ticket, masih kesulitan, jadi diganti dengan
					// cek apakah ini join & seting tidak contain board, karena kalau dia join dan tidak kontain board, maka pasti dia contain master; that's why langkah ini harus punya guidParam as guid_master nya;
					if ($guidParam == null ) {
						throw new StoreResourceFailedException("INI PROSES JOIN, TOLONG SCAN MASTER TERLEBIH DULU!",[
							'note' => 'need guid master',
							'node' => json_decode( $this, true ),
						]);
					}
				}

				$guid = (!is_null($guid)) ? $guid['guid_ticket'] : null;
			}

			if($this->getModelType() == 'master'){
				$guid = (!is_null($guid)) ? $guid['guid_master'] : null;
			}

		}else {
			// tadinya ga ada is join, something went wrong. so add the is join to verify 
			if( ($this->getModelType()=='board') && ($this->isJoin()) && ($this->isSettingContain('board')) ){
				if ($guidParam == null ) {
					throw new StoreResourceFailedException("INI PROSES JOIN, TOLONG SCAN TICKET ATAU MASTER DULU!",[
						'note' => 'need guid_ticket or guid_master',
						'node' => json_decode( $this, true ),
					]);
				}
			}

			if( ($this->getModelType() == 'ticket') && ($this->isJoin()) && ($this->isSettingContain('ticket')) && ($this->isSettingContain('master')) ){
				if ($guidParam == null ) {
					throw new StoreResourceFailedException("INI PROSES JOIN, TOLONG SCAN MASTER DULU!",[
						'note' => 'BUTUH GUID MASTER',
						'node' => json_decode( $this, true ),
					]);
				}
			}

			$guid = ($guidParam == null )?  $this->generateGuid() : $guidParam ;
		}

		// it can triggered after scanner & model has been set; 
		if ($this->getModelType() == 'ticket' ) {
			if($guidParam != null){
				// verify that if guidParam is also guid_ticket, throw exception
				$this->isGuidTicket($guidParam);

				$this->setGuidMaster($guidParam);
			}
			$this->setGuidTicket($guid);
			// we have problem here, we cannot assign master guid to ticket, since guid always assign ton 
			// guid_ticket;

			// we need to determined if we had last guid or no;
			// if we had, that's mean guid parameter should be as guid_master;
			// if not, 
		}

		else if($this->getModelType() == 'master'){
			$this->setGuidMaster($guid);
		}

		else /*($this->getModelType() == 'board')*/{
			// cek column setting, this step is join atau bkn,
			if($this->isJoin()){
				/*this changes is to avoid same guid_master & guid_ticket in input 1 audio which is obiously data anomaly*/
				if( $this->isSettingContain('master') ){
			 		$this->setGuidMaster($guid);
				}else{
					$this->setGuidTicket($guid);
				}

			}else{
				$guid = $this->dummy_id; //untuk board yg NG sebelum join; guidnya kita ganti dummy id
				// supaya bisa dapet ketika dicari di table repair;
			}
		}

		$this->setUniqueId($guid);
	}

	public function isGuidTicket($guidParam){

		$uniqueColumn = $this->getUniqueColumn();
		$uniqueId = (is_null($guidParam))? $this->getUniqueId(): $guidParam;

		$result = Ticket::select('id')
		->where( 'guid_ticket', $uniqueId )
		->first();

		if ($result){
			throw new StoreResourceFailedException("Tolong Scan LCD atau BOARD, Jangan Dummy Panel lagi. atau matikan Is join Active dengan cara klik tombolnya", [
				'message' => 'tolong ulangi scan LCD atau Boardnya sampai berhasil!',
				'dev_message'=>'ini terjadi karena guid master yg dikirim front end, telah digunakan sbg guid ticket dummy panel lain',
				'guid' => $uniqueId
			]);
			
		};
		// if ( ( $result && ($guidParam !== '') ) );
	}

	private function setUniqueId($guid){
		$this->unique_id = $guid;
	}

	public function getUniqueId(){
		return $this->unique_id;
	}

	public function getUniqueColumn(){
		return $this->unique_column;
	}

	public function getDummyParent(){
		$tmp = str_split($this->dummy_id);

		if(strlen($this->dummy_id) == 16 ){
			$tmp[7] = 0;
			$tmp[8] = 0;
		}else if(strlen($this->dummy_id) == 24 ){
			$tmp[12] = 0;
			$tmp[13] = 0;
		}

		return implode('', $tmp );
	}

	public function getDummyId(){
		return $this->dummy_id;
	}

	public function ignoreSideQuery($query){
		if( $this->getModelType() == 'board' ){
			
			$tmp = $this->getDummyParent();
				
			if(strlen($this->dummy_id) == 16 ){
				$sideIndex = 6;
			}else if (strlen($this->dummy_id) == 24 ){
				$sideIndex = 14;
			}else{
				// default side index yg sekarang adalah 14; karena ini yg berlaku;
				$sideIndex = 14;
			}
			
			$parentA = $tmp;
			$parentA[$sideIndex] = 'A';

			$parentB = $tmp;
			$parentB[$sideIndex] = 'B';

			$a = $this->dummy_id;
			$a[$sideIndex] = 'A';
			$b = $this->dummy_id;
			$b[$sideIndex] = 'B';

			$query->where($this->dummy_column, $a )
				->orWhere($this->dummy_column, $b )
				->orWhere($this->dummy_column, $parentA )
				->orWhere($this->dummy_column, $parentB );
		}else {
			// ticket & master
			$query->where( $this->dummy_column, $this->dummy_id );	
		}
	}

	// we need to changes this method to acomodate the masters 
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

		$guid = $this->model
			->where( function($query){ $this->ignoreSideQuery($query); } )
			->orderBy('id', 'desc');

		if( $this->getModelType() == 'ticket' ){
			$guid = $guid->select([
				'guid_ticket'
			])->where('guid_master', null )
			->where('guid_ticket','!=', null );
		}else if($this->getModelType() == 'master'){
			$guid = $guid->select([
				'guid_master'
			])->where('serial_no', null )
			->where('guid_master','!=', null );
		}

		return $guid = $guid->first();
	}

	public function getGuidTicket(){
		return $this->guid_ticket;
	}

	public function setGuidTicket($guid){
		if ($this->getModelType() == 'board') {
			// if it has a sibling, then
			if( $this->hasTicketSibling($guid) ){
				//verify it has same modelname and lotno
				$this->verifyModelnameAndLotno('ticket', $guid);
				// if failed, throw error that the previous board has different modelname & lotno
			}

			if($this->hasMasterSibling($guid) ){
				// verify it has same modelname and lotno
				$this->verifyModelnameAndLotno('master', $guid);
				// if failed, throw error that the previous board has different modelname & lotno
			}
		}

		$this->guid_ticket = $guid;
	}

	public function setGuidMaster($guid){
		if ($this->getModelType() == 'board') {

			// if it has a sibling, then
			if( $this->hasTicketSibling($guid) ){
				//verify it has same modelname and lotno
				$this->verifyModelnameAndLotno('ticket', $guid);
				// if failed, throw error that the previous board has different modelname & lotno
			}

			if($this->hasMasterSibling($guid) ){
				// verify it has same modelname and lotno
				$this->verifyModelnameAndLotno('master', $guid);
				// if failed, throw error that the previous board has different modelname & lotno
			}
		}

		$this->guid_master = $guid;
	}

	public function getGuidMaster(){
		return $this->guid_master;
	}


	/*
	* @parameter = 'ticket' or 'master'
	* this method called in setGuidMaster & setGuidTicket for verification
	* guid parameter is a must since this method called before initGuid finish
	*/
	public function verifyModelnameAndLotno($type = 'ticket', $guid = null ){
		// return setting('admin.strict_checking');
		if(function_exists('setting')){
			// jika pengaturan admin.strict_checking == false, maka method ini langsung return saja, gausah dilanjut.
			// atau dengan kata lain, jangan test
			if (setting('admin.strict_checking')) {
				// get board based on guid; wheter it is 
				if($type == 'ticket'){
					$prevBoard = Board::select(['id','modelname','lotno'])->where( 'guid_ticket' , $guid )
						->orderBy('created_at', 'desc')
						->first();

					// we need to add checking boards from master here;
				}

				if($type == 'master'){
					$prevBoard = Board::select(['id','modelname','lotno'])->where( 'guid_master' , $guid )
						->orderBy('created_at', 'desc')
						->first();
				}

				// due to circular dependencies, we cannot use $this->modelname here, instead, we use user parameter;
				$modelname = (isset($this->modelname)) ? $this->modelname : $this->parameter['modelname'];

				if( $prevBoard->modelname != $modelname ){
					// if current model sent by user is different from previous insalled board model, return confirmation view
					throw new StoreResourceFailedException($this->confirmation_view_error, [
						'message' => "BOARD MODEL YANG ANDA SCAN BERBEDA DENGAN BOARD MODEL SEBELUMNYA. BOARD MODEL SEKARANG = '{$modelname}' , BOARD MODEL SEBELUMNYA '{$prevBoard->modelname}'!",
						'node' => json_decode($this, true ),
						'prevBoard' => $prevBoard,
						'server-modelname' => $prevBoard->modelname,
					]);
				}

				if( $prevBoard->lotno != $this->lotno ){
					throw new StoreResourceFailedException("LOT NUMBER BOARD YG ANDA SCAN BERBEDA DENGAN LOT NUMBER BOARD SEBELUMNYA. LOT NUMBER SEKARANG '{$this->lotno}' , LOT NUMBER SEBELUMNYA '{$prevBoard->lotno}'", [
						'message' => 'for jein developer : due to circular dependencies, we cannot use current node modelname. instead we use user parameter',
						'node' => json_decode($this, true ),
						'prevBoard' => $prevBoard,
					]);
				}		
			}
		}
	}


	/*
		@void, to verify if the parameter modelname sent by front end is correct by comparing the data with previous children board;
	*/
	public function verifyParameterModelname(){
		$boardChildren = $this->getBoardChildren(); //ambil board anak;
		
		if( $boardChildren !== null ){
			// compare antara pengaturan dengan board anak;
			if( $boardChildren['modelname'] != $this->parameter['modelname'] ){
				// get the board children to compare the model
				throw new StoreResourceFailedException($this->confirmation_view_error, [
					'node' => json_decode($this, true ),
					'server-modelname' => $boardChildren['modelname']
				]);
			}
		}
	}

	// only work for board because only 
	public function hasTicketSibling($guidParam = null ){
		$guid = ( !is_null( $guidParam)) ? $guidParam : $this->getGuidTicket();
		if( $this->getModelType() == 'board' ){
			return Board::where('guid_ticket', '!=', null )
			->where('guid_ticket', $guid )
			->exists();
		}
	}
	// only work for board
	public function hasMasterSibling($guidParam = null){
		$guid = ( !is_null( $guidParam)) ? $guidParam : $this->getGuidMaster();
		
		if( $this->getModelType() == 'board' ){
			return Board::where('guid_master', '!=', null )
			->where('guid_master', $guid )
			->exists();
		}
	}

	/*
		bool @hasChildren() 
		comparing current children qty with $this->lineprocess->joinQty
	*/
	public function hasChildren(){
		if($this->getModelType() == 'board' ){
			return false;
		}

		$joinQty = $this->getLineprocess()->join_qty;
		$totalChildren = 0; //default value of total children

		if($this->getModelType() == 'master'){
			$guid_master = $this->getGuidMaster();

			$ticket = Ticket::distinct()
			->where('guid_master', $guid_master )
			->where('scanner_id', $this->scanner_id )
			->count('ticket_no');

			$board = Board::distinct()
			->where('guid_master', $guid_master )
			->where('scanner_id', $this->scanner_id )
			->count('board_id');

			$totalChildren = $ticket + $board;
		}

		if($this->getModelType() == 'ticket' && ($this->isSettingContain('master') === false ) ){
			/* 
				betul ga ini beneran anak dari ticket itu sendiri ??
				jangan2 yg masuk kesini itu master yg sedang scan anaknya yaitu tickets;
			*/
			$boards = Board::distinct()
			->where('guid_ticket', $this->getGuidTicket() )
			->where('scanner_id', $this->scanner_id )
			->count("board_id");

			$parts = Part::distinct()
			->where('guid_ticket', $this->getGuidTicket() )
			->where('scanner_id', $this->scanner_id )
			->count("barcode");

			$totalChildren = $boards + $parts;
		}else{
			// ini ticket yg contain master. ticket as children
			$guid_master = $this->getGuidMaster();

			$ticket = Ticket::distinct()
			->where('guid_master', $guid_master )
			->where('scanner_id', $this->scanner_id )
			->count('ticket_no');

			$board = Board::distinct()
			->where('guid_master', $guid_master )
			->where('scanner_id', $this->scanner_id )
			->count('board_id');

			$totalChildren = $ticket + $board;
		}

		$this->joinTimesLeft = $joinQty - $totalChildren;
		return ( $totalChildren >= $joinQty );
	}

	public function getJoinTimesLeft(){
		return $this->joinTimesLeft;
	}

	/*
		board getBoardChildren() will return boards with the same guid with current node;
		it is required guid in order to run
	*/
	public function getBoardChildren(){
		if($this->getModelType() == 'board' ){
			return null; // required due to caller if statement
		}

		if($this->getModelType() == 'master'){

			$board = Board::where('guid_master', $this->getGuidMaster() )
			->first();

			return $board;
		}

		if($this->getModelType() == 'ticket'){
			return Board::where('guid_ticket', $this->getGuidTicket() )
			->first();
		}		

	}

	public function isGuidGenerated($paramType = null ){
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

		if( is_null($paramType) ){
			$paramType = $this->getModelType();
		}

		if( $paramType  == 'ticket'){
			return $this->model
				// ->where( 'scanner_id' , $this->scanner_id  )
				->where( $this->dummy_column, $this->dummy_id )
				->where('guid_master', null )
				->exists();
		}else if ($paramType == 'master'){
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

	public function getModelType(){
		return $this->model_type;
	}

	public function isExists($status=null, $judge=null, $is_solder = null ){
		if(is_null($this->lineprocess)){
			throw new StoreResourceFailedException("this lineprocess is not set", [
				'message' => $this->lineprocess
			]);
		}

		if($this->lineprocess['type'] == 1 ){
			// masuk kesini jika internal;
			$model = $this->model
			->where( 'scanner_id' , $this->scanner_id  )
			->where( function($query){ $this->ignoreSideQuery($query); } );
			// ->where( $this->dummy_column, $this->dummy_id );

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

			if($this->getModelType() == 'master'){
				// make sure the finished dummy can be reuse;
				$model = $model->where('serial_no', null );
			}else if($this->getModelType() == 'ticket'){
				$model = $model->where('guid_master', null );
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
	        $res = $client->get($url, [	
	    		'query' => [
	    			'board_id'	=> $this->parameter['board_id']
	    		],
		 		'headers' => ['Content-type' => 'application/json'],
	        ]);

	        $result = json_decode( $res->getBody(), true );
	        // it's should return boolean
	        return ($result['success'] && $result['data']['userjudgment'] != 'NG');
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

		if($this->getModelType() != 'master'){
			$model->guid_ticket = $this->guid_ticket;
		}

		$model->scanner_id = $this->scanner_id;
		$model->status = $this->status;
		$model->judge = $this->judge;
		$model->scan_nik = $this->nik;

		if ($this->getModelType() == 'board' ) {
			$model->modelname = $this->modelname;
			$model->lotno = $this->lotno;
		}

		$this->updateGuidSibling();

		# insert the to critical parts jika critical parts tidak null;
		if (!is_null($this->getCriticalPart())) {
			# code...
			$criticalParts = $this->getExtractedCriticalParts();
			if(method_exists($this, 'insertIntoCritical')){
				if ($this->getStatus() == 'IN') {
					# only run this method when it's IN. // if it's OUT, gausah.
					$this->insertIntoCritical($criticalParts, $this->getUniqueId() );
				}
			}
		}

		$isSaveSuccess = $model->save();
		if( $isSaveSuccess && ( $this->getModelType() == 'master' ) && ($this->getJudge() == 'NG') ){
			$this->insertSymptom($model);
		}

		return $isSaveSuccess; //true or false;
	}

	public function delete(){
		if($this->hasChildren() == false ){

			$model =  $this->model->where($this->dummy_column, $this->dummy_id )
			->where('scanner_id', $this->scanner_id );

			if($this->getModelType() == 'master'){
				// make sure the finished dummy can be reuse;
				$model = $model->where('serial_no', null );
			}else if($this->getModelType() == 'ticket'){
				$model = $model->where('guid_master', null );
			}

			$model = $model->delete();
			return $model;
		}
	}

	public function insertSymptom($model){
		$symptom = Symptom::select(['id'])
		->whereIn('code', $this->parameter['symptom'] )
		->get();
		
		if($symptom->isEmpty()){
			return true; // kalau empty data symptom yg di pass nya, langsung return saja;
		}

		$symptoms = [];
		foreach ($symptom as $key => $value) {
			$symptoms[] = $value['id'];
		}

		$model->symptoms()->sync($symptoms);
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
			// it'll need to be changed due to changes in big system
			$board_id = $this->getModelCode();
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

		/*if($this->getModelType() == 'ticket'){
			if (is_null($this->guid_ticket)) {
				throw new StoreResourceFailedException("guid ticket is null", [
					'node' => json_decode($this, true),
				]);
			}
	
			// kalau belum, kita setup model based on user parameter;
			// ini untuk meng akomodir kebutuhan scan panel sebelumn proses join dengan board;
			// detect model from dummy card;
			$this->verifyParameterModelname();	

			$model = $model->where('name', $this->parameter['modelname'] );

		} else if($this->getModelType() == 'master') {
			// detect model from dummy card;
			$this->verifyParameterModelname();

			$model = $model->where('name', $this->parameter['modelname'] );
		}else{
			// this is from bigs db
			$model = $model->where('code', $board_id );
		}*/
		if($this->getModelType() == 'board'){
			$model = $model->where('code', $board_id );
		}else{
			if (is_null($this->guid_ticket) && ($this->getModelType() == 'ticket') ) {
				throw new StoreResourceFailedException("guid ticket is null", [
					'node' => json_decode($this, true),
				]);
			}

			$this->verifyParameterModelname();
			$model = $model->where('name', $this->parameter['modelname'] );
		}

		$model = $model->first();

		if ($model == null) {
			throw new StoreResourceFailedException("ANDA SCAN '{$board_id}'. PENGATURAN DATA DENGAN NAMA MODEL '{$this->parameter['modelname']}' TIDAK DITEMUKAN DI BOARD ID GENERATOR SYSTEM! PASTIKAN CURRENT MODEL CONFIG BENAR!", [
				'node' => json_decode($this, true ),
				'model_type' => $this->getModelType(),
				'scanned_value' => $board_id,
			]);

		}

		$this->setBoard($model);
		$this->setModelname($model->name);

		return $this;
	}

	public function setBoard($model = null){
		// $this->board['name'] = $model['name'];
		// $this->board['pwbname'] = $model['pwbname'];
		$this->board = $model;
	}

	// @ return boolean; indicate that the column setting is contain board
	public function isSettingContainBoard(){
		return $this->isSettingContain('board');
	}

	public function isSettingContain($modelType = 'board'){
		$result = false;
		foreach ($this->column_setting as $key => $setting ) {
			$settingTable = str_singular( $setting['table_name'] );
			if($settingTable == $modelType ){
				$result = true;
			}
		}
		return $result;
	}

	public function setModelname($modelname){
		$this->modelname = $modelname;
	}

	public function getModelname(){
		return $this->modelname;
	}

	public function setLotno($parameterBoardId){
		if($this->getModelType() != 'board'){
			// kalau bukan board, gausah set lot no
			return;
		}

		if( strlen($parameterBoardId) <= 24 ){
			$lotno = substr($parameterBoardId, 16, 4);
		}else{
			// untuk 24 char
			$lotno = substr($parameterBoardId, 9, 3);
		}
		// kalau hasil substr ga ketemu, dia bakal return false;
		// untuk mengatasi itu, maka simpan saja empty string instead of 0;
		$lotno = (!$lotno)? '':$lotno;

		$this->lotno = $lotno;
	}

	public function getLotno(){
		return $this->lotno;
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
				->where( function($query){ $this->ignoreSideQuery($query); } )
				->orderBy('id', 'desc'); //order menurun

			if($this->getModelType() == 'master'){
				// make sure the finished dummy can be reuse;
				$model = $model->where('serial_no', null );
			}else if($this->getModelType() == 'ticket'){
				$model = $model->where('guid_master', null );
			}

			$model = $model->first();

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
		$endpoint = Endpoint::select()->find($this->lineprocess['endpoint_id']);
		if(is_null($endpoint)){
			throw new StoreResourceFailedException("endpoint with id ".$this->lineprocess['endpoint_id']." is not found", [
				'lineprocess' => $this->lineprocess,
			]);
		}

		$url = $endpoint->url; //'http://localhost/mapros_system54/public/api/aoies';
		$client = new Client();
		// $url = "https://api.github.com/repos/guzzle/guzzle";
        $res = $client->get($url, [	
    		'query' => [
    			'board_id'	=> $this->parameter['board_id']
    		],
	 		'headers' => ['Content-type' => 'application/json'],
        ]);

        if( $res->getStatusCode() != 200 ){
        	throw new StoreResourceFailedException("Something wrong to your external code data. CALL IT!", [
        		'status_code' => $res->getStatusCode(),
        		'body' => $res->getBody()
        	]);
        }

        $result = json_decode( $res->getBody(), true );

        if( /*array_key_exists('judge', $result ) || */$result == null ){
        	// return $result;
        	throw new StoreResourceFailedException("SUMBER EXTERNAL HARUS SELALU MENGANDUNG 'JUDGE' & 'STATUS'!", [
        		'result' => $result,
        		'url' => $url,
        		'response' => $res->getStatusCode() //json_decode( json_encode($res), true )
        	]);
        }
		
		// end point should always contain status and judge;
        if( ($result['success']) && ($result['judge'] != 'NG') ){
        	$this->setStatus('OUT');
			$this->setJudge("OK");
        }else{
        	$this->setStatus('OUT');
			$this->setJudge("NG");
        }
        
        $this->setStep($result);
	}

	// this method triggered by loadStep();
	private function setStep($model){
		$this->step = $model;
	}

	public function getStep(){
		return $this->step;
	}

	public function setJudge($judge){
		$this->judge = $judge;
	}

	public function getJudge(){
		return $this->judge;
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

		if (is_null( $board)) {
			throw new StoreResourceFailedException("Board is not defined yet!", [
				'message' => 'getSequence method dependence to board',
				'node' => json_decode($this, true )
			]);
		}

		if (is_null($scanner)) {
			throw new StoreResourceFailedException("Scanner is null", [
				'message'=>'getSequence method dependence to setScannerId method'
			]);
		}

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
			}else {
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
			'join_qty', //added to get in hasChildren
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
		/*return array of lineprocess*/
		/*
			'id',
			'name',
			'type',
			'std_time',
			'endpoint_id',
			'join_qty', //added to get in hasChildren
		*/
		return $this->lineprocess;
	}

	public function initCurrentPosition(){
		if( is_null($this->process) ){
			if ($this->getModelType() == 'board' ) {
				$pwbname  = $this->board['pwbname'];
			}else {
				// disini kita harus determine wheter it is panel or mecha;
				$pwbname  = $this->getIdType(); 
			}

			throw new StoreResourceFailedException("PENGATURAN PROSES TIDAK DITEMUKAN. klik see details untuk info lebih lanjut!", [
                'message' => 'Pengaturan sequence dengan modelname = "'.$this->board['name'].'", pwbname="'.$pwbname.'", dan line_id="'.$this->scanner['line_id'].'" tidak ditemukan! tolong segera buat!',
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
			throw new StoreResourceFailedException("SCAN ".$this->getIdType()." '".$this->getDummyId()."' TIDAK SEHARUSNYA DILAKUKAN DI STEP INI.", [
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

			// set lineprocess
			$this->setLineprocess($this->scanner['lineprocess_id']);
			// set column setting;
			$this->initColumnSetting();

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
	* this method is run by save method 
	*/
	public function updateGuidSibling(){
		/*
		* yang meng update itu child yang sudah punya guid, dia update teman temannya.
		* bukan parent yang yang punya child;
		*/

		if ($this->getModelType() == 'board') {
			# we need to determine which column need to update, guid ticket or guid master 

			// jika guid ticket nya tidak null, maka update;
			if($this->guid_ticket!= null){
				// update yang guid ticket nya masih null;
				// ketika join;
				Board::where('guid_ticket', null )
				// ->where('board_id', $this->parameter['board_id'] )
				->where( function($query){ $this->ignoreSideQuery($query); } )
				->where('lotno', $this->lotno )
				->update(['guid_ticket' => $this->guid_ticket ]);
			}

			if($this->guid_master != null){
				// update yang guid ticket nya masih null;
				// ketika join;
				Board::where('guid_master', null )
				// ->where('board_id', $this->parameter['board_id'] )
				->where( function($query){ $this->ignoreSideQuery($query); } )
				->where('lotno', $this->lotno )
				->update(['guid_master' => $this->guid_master ]);
			}
		}

		if($this->getModelType() == 'ticket'){
			// get guid master;
			if($this->guid_master != null){
				// get ticket & board that has same guid ticket

				// we need to check guid_ticket & guid_master berbeda;
				Ticket::where('guid_master', null )
				->where('guid_ticket', $this->guid_ticket )
				->update(['guid_master' => $this->guid_master ]);

				Board::where('guid_master', null )
				->where('guid_ticket', $this->guid_ticket )
				->update(['guid_master' => $this->guid_master ]);
			}
		}
	}

	public function updateChildren(){
		// gausah running method ini kalau bukan langkah join
		if ( $this->isJoin() == false ) {
			return;
		}

		// check type model;
		if( $this->getModelType() == 'ticket' ){
			// get child, that has already scan by the same scanner & has same guid_ticket
			$child = Board::where('guid_ticket', $this->guid_ticket )
				->where('scanner_id', $this->scanner_id )
				->orderBy('id', 'desc')
				->first();

			if($child!=null){
				// jika last status dari board adalah 'IN'
				if ($child->status == 'IN') {
					// insert out nya untuk si child;
					$newBoard = new Board([
				    	'board_id' => $child->board_id,
				    	'guid_master' => $child->guid_master,
				    	'guid_ticket' => $child->guid_ticket,
				    	'scanner_id' => $this->scanner_id,
				    	'modelname'	=> $child->modelname,
				    	'lotno'	=> $child->lotno,
				    	'status' => 'OUT',
				    	'judge' => 'OK',
				    	'scan_nik' => $this->parameter['nik'],
				    ]);

				    $newBoard->save();
				}
			}
		}

		if( $this->getModelType() == 'master' ){
			// this if is to avoid updating unnecessary table;
			// so it's only updating in join setting;
			if($this->isSettingContainBoard()){
				// board;
				$child = Board::where('guid_master', $this->guid_master )
					->where('scanner_id', $this->scanner_id )
					->orderBy('id', 'desc')
					->first();

				if($child!=null){
					// jika last status dari board adalah 'IN'
					if ($child->status == 'IN') {
						// insert out nya untuk si child;
						$newBoard = new Board([
					    	'board_id' => $child->board_id,
					    	'guid_master' => $child->guid_master,
					    	'guid_ticket' => $child->guid_ticket,
					    	'scanner_id' => $this->scanner_id,
					    	'modelname'	=> $child->modelname,
					    	'lotno'	=> $child->lotno,
					    	'status' => 'OUT',
					    	'judge' => 'OK',
					    	'scan_nik' => $this->parameter['nik'],
					    ]);

					    $newBoard->save();
					}
				}
			}

			if($this->isSettingContain('ticket')){
				// ticket;
				$ticket = Ticket::where('guid_master', $this->guid_master )
					->where('scanner_id', $this->scanner_id )
					->orderBy('id', 'desc')
					->first();

				if($ticket != null ){
					if($ticket->status == 'IN'){
						$newTicket = new Ticket([
					    	'ticket_no' => $ticket->ticket_no,
					    	'guid_master' => $ticket->guid_master,
					    	'guid_ticket' => $ticket->guid_ticket,
					    	'scanner_id' => $this->scanner_id,
					    	'status' => 'OUT',
					    	'judge' => 'OK',
					    	'scan_nik' => $this->parameter['nik'],
					    ]);
	
					    $newTicket->save();
					}
				}
			}
		}
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