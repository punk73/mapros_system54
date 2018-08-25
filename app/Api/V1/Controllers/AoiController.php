<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use App\Api\V1\Requests\AoiRequest;
use App\AOI;

class AoiController extends Controller
{	
	public function index(AoiRequest $request){


		$aoi = AOI::select([
			'barcode',
			'userjudgment'
		])->where('barcode', $request->board_id );

		return [
			'success' => true,
			'data' => $aoi->first(),
			'status' => $aoi->exists()
		];
	}

	
}
