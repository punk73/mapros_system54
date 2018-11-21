<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineprocessStart extends Model
{
    protected $table= 'lineprocess_starts';
    
    protected $primaryKey = 'lineprocess_id';

    protected $fillable = [
    	'lineprocess_id',
    	'start_id'
    ];
}
