<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\MainController as Api;
use App\Api\V1\Requests\BoardRequest;
use Dingo\Api\Exception\StoreResourceFailedException;
use Session;

class JoinController extends Controller
{
	public function index(Request $request){
		$response = Session::get('response');
		return view('join-view', ['response'=>$response]);
	}

    public function post(BoardRequest $request){
    	// here comne the logic for join;

		// we identify how many board_id that sent,
		$api = new Api;

    	try {
    		$response = $api->store($request);
    	} catch (StoreResourceFailedException $e) {
    		return $this->handleError($e);
    	}

    	return back()->with(['response' => $response]);
    }

    public function handleError($e){
    	$error = [
    		'message' => $errors->getMessage(),
    		'errors'  => $errors->getErrors()
    	];

    	return back()->with(['error' => $error]);

    }
}
