<?php

/**
 * 
 */
namespace App\Observers;
use App\Lineprocess;
use App\LineprocessStart;

class LineprocessObserver
{
	public function created(Lineprocess $lineprocess){
		$lineprocessStart = new LineprocessStart([
			'lineprocess_id' => $lineprocess->id,
	    	'start_id' => $lineprocess->id
	    ]);

	    $lineprocessStart->save();
	}

	public function deleted(Lineprocess $lineprocess){
		$lineprocessStarts = LineprocessStart::where('lineprocess_id', $lineprocess->id )->get();
		foreach ($lineprocessStarts as $key => $lineprocessStart ) {
			# code...
			$lineprocessStart->delete();
		}
	}
}