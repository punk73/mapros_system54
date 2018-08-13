<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guid extends Model
{
    protected $table = 'guids';

    protected $fillable = [
    	'guid'
    ];
}
