<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;;

class BoardLocation extends Pivot
{
    protected $table = 'board_location';

    public function symptoms(){
    	// 
    	return $this->belongsToMany('App\Symptom', 'board_location_symptom', 'board_location_id', 'symptom_id' )
    	->withTimestamps();
    }
}
