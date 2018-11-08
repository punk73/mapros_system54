<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CriticalNode extends Model
{
    protected $table = 'critical_node';

    protected $fillable = [
    	'critical_id',
    	'unique_id'
    ];
}
