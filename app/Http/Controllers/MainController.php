<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\MainController as Api;
use App\Api\V1\Requests\BoardRequest;
use Dingo\Api\Exception\StoreResourceFailedException;
use Session;

class MainController extends Controller
{   
    public function index(){
        $error = Session::get('error');

        return view('main', ['error'=>$error]);
    }

    public function post(BoardRequest $request ){
    	
    	$api = new Api;

    	try {
    		$response = $api->store($request);
    	} catch (StoreResourceFailedException $e) {
    		// print_r($e);
    		if( $e->getMessage() == 'view' ){
    			$data = $e->getErrors();
    			// return view dengan tambahan textfield
    			return $this->viewJoin($data);
    		};

    		return $this->handleError($e);
    	}
    	
    	return json_encode([
    		$request->all(),
    		// $response
    	]);

    	return redirect('/', [
    		'response' => $response
    	]);

    }

    public function viewJoin($response){
    	return redirect('join')->with([
            'response' => $response
        ]);
    }

    public function handleError($errors){
    	$error = [
    		'message' => $errors->getMessage(),
    		'errors'  => $errors->getErrors()
    	];

    	return redirect('/')->with(['error' => $error]);
    }
}
