<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\MainController as Api;
use App\Api\V1\Requests\BoardRequest;

class MainController extends Controller
{
    public function post(BoardRequest $request ){
    	
    	$api = new Api;
    	$response = $api->store($request);

    	// return json_encode($response);
    	if ($response['success'] == false && $response['success'] == 'view' ) {
    		// return 
    	}
    	
    	return view('main', [
    		'response' => $response
    	]);
    }
}
