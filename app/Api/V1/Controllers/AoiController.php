<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use App\Api\V1\Requests\AoiRequest;
use App\AOI;

class AoiController extends Controller
{
	public function index(AoiRequest $request)
	{

		$boardid = $request->board_id;

		$aoi = AOI::select([
			'barcode',
			'userjudgment'
		])->where('barcode', $boardid);

		if ($aoi->first()) {
			return [
				'success' => true,
				'data' => $aoi->first(),
				'status' => 'OUT',
				'judge' => ($aoi->exists()) ? 'OK' : 'NG'
			];
		} 

		// check board DT key
		$isKeyBoard = $this->checkKeyBoard($boardid);
		if($isKeyBoard=='true')
		{
			$boardid = $this->changeToDOBoard($boardid);
		}

		$changeToMother = $this->changeToMotherCode($boardid);
		$aoi_convert = AOI::select([
			'barcode',
			'userjudgment'
		])->where('barcode', $changeToMother);

		if ($aoi_convert->first()) {
			return [
				'success' => true,
				'data' => $aoi_convert->first(),
				'status' => 'OUT',
				'judge' => ($aoi_convert->exists()) ? 'OK' : 'NG'
			];
		} 

		$changeToSideA = $this->changeToSideA($boardid);
		$aoi_sideA = AOI::select([
			'barcode',
			'userjudgment'
		])->where('barcode', $changeToSideA);


		if (!$aoi_sideA->first()) {
			// "Data '{$request->board_id}' NG atau tidak ditemukan di SMT!!"
			throw new StoreResourceFailedException("Board '{$request->board_id}' belum inspect AOI atau NG AOI. Silahkan confirm SMT ", [
				'message' => 'data tidak ditemukan pada table AOI!'
			]);
		}

		return [
			'success' => true,
			'data' => $aoi_sideA->first(),
			'status' => 'OUT',
			'judge' => ($aoi_sideA->exists()) ? 'OK' : 'NG'
		];

		// if (!$aoi->first()) {
		// 	// "Data '{$request->board_id}' NG atau tidak ditemukan di SMT!!"
		// 	throw new StoreResourceFailedException("Board '{$request->board_id}' belum inspect AOI atau NG AOI. Silahkan confirm SMT ", [
		// 		'message' => 'data tidak ditemukan pada table AOI!'
		// 	]);
		// }

		// return [
		// 	'success' => true,
		// 	'data' => $aoi->first(),
		// 	'status' => 'OUT',
		// 	'judge' => ($aoi->exists()) ? 'OK' : 'NG'
		// ];
	}

	public function changeToMotherCode($boardid)
	{
		return substr_replace($boardid, '00', 12, -10);
	}
	public function changeToDOBoard($boardid)
	{
		// Y-J-5-2-2-4-M-0-1-D-O-_-0-1-A-7-0-1-5-A-0-0-1-3
		// 0-1-2-3-4-5-6-7-8-9-0-1-2-3-4-5-6-7-8-9-0-1-2-3
		// $boardid='YJ5224M01DO_01A7015A0013';
		return substr_replace($boardid, 'DO', 9, 2);
	}

	public function checkKeyBoard($boardid)
	{
		// Y-J-5-2-2-4-M-0-1-D-O-_-0-1-A-7-0-1-5-A-0-0-1-3
		// 0-1-2-3-4-5-6-7-8-9-0-1-2-3-4-5-6-7-8-9-0-1-2-3
		// $boardid='YJ5224M01DO_01A7015A0013';
		
		$is_key = substr($boardid,9,2);
		if($is_key=="KY")
		{
			return 'true';
		}
		return 'false';

	}
}
