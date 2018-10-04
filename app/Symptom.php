<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    public function Masters(){
    	return $this->belongsToMany('App\Master');
    }
}
