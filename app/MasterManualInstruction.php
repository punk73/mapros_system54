<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterManualInstruction extends Model
{
    protected $fillable = [
    	'content',
		  'modelname',
    ];
}
