<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class Board extends Model
{
    protected $table = 'boards';

    protected $fillable = [
    	'board_id',
    	'guid_master',
    	'guid_ticket',
    	'scanner_id',
    	'status',
    	'judge',
    	'scan_nik',
    ];
}
