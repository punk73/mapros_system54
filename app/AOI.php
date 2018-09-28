<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AOI extends Model
{
	// protected $connection='sqlsrv';
	public function __construct(){
		$this->connection = env('DB_CONNECTION1', 'sqlsrv');
	}

    protected $table = 'tblAOIResultBoard';

}
