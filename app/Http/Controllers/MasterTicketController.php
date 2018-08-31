<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\TicketMasterController as Ticket;
use App\Api\V1\Requests\TicketRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QRCode;
use Storage;

class MasterTicketController extends Controller
{
    public function index(){

    }

    public function post(TicketRequest $request){
    	$ticket = new Ticket;
    	$data = $ticket->store($request);
		    	
    	foreach ($data['data'] as $key => $value) {
			//generate qrcode;
			$file = storage_path('app/public/');
			$file .= $value.'.png';
			//save to storage
			if(!Storage::exists($file)){
				QRCode::format('png')->size(57)->generate($value, $file );
			}//save src in new array 
			
			$item = [
				'code'  => $value,
				'img'	=> Storage::url($value .'.png'),
			];

			$data['data'][$key] = $item;
    	}

    	// return json_encode($data);

    	return view('print-qr',['data' => $data['data']] );
    }
}
