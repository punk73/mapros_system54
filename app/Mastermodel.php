<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mastermodel extends Model
{	
	public function __construct(){
		// parent::__construct(); 
		$this->connection = env('DB_CONNECTION2', 'sqlsrv1'); //the default value is for productions env to avoid error
	}

    protected $table = 'models';

}
