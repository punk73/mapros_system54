<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Firebird\Model;

class Weightcontrol extends Model
{
    protected $table = 'WEIGHTCONTROL';

    public function __construct(){
		$this->connection = env('DB_CONNECTION_FB', 'firebird');
	}
}
