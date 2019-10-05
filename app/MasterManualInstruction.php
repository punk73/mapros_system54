<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class MasterManualInstruction extends Model
{
    protected $table = "master_manual_instructions";
    protected $fillable = [
    	'content',
		  'modelname',
    ];
}
