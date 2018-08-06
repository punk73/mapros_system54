<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mastermodel extends Model
{
	protected $connection='sqlsrv1';
    protected $table = 'models';
}
