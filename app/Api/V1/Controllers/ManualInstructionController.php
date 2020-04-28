<?php

namespace App\Api\V1\Controllers;

use Config;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use App\MasterManualInstruction;

class ManualInstructionController extends Controller
{
    public function index(Request $request) {
        $modelname = $request->modelname;

        $data = MasterManualInstruction::where('modelname',$modelname)->orderBy('id', 'desc')->count();

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
