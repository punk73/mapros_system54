<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mastermodel extends Model
{	
	public function __construct(){
		// parent::__construct(); 
		$this->connection = env('DB_CONNECTION2', 'mysql2');
	}

    protected $table = 'models';

}
