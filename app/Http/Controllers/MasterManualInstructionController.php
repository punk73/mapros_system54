<?php

namespace App\Http\Controllers;

use App\MasterManualInstruction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Facades\Voyager;

class MasterManualInstructionController extends VoyagerBaseController
{
    public function store(Request $request)
    {
        // do something here;
        $params = $this->validate($request, [
            "content"   => "required",
            "modelname" => "required"
        ]);

        $exist = MasterManualInstruction::where('content', $request->content)
            ->where('modelname', $request->modelname)
            ->exists();

        if($exist) {
            return back()->withErrors("DATA DENGAN CONTENT '{$request->content}' DAN MODELNAME '{$request->modelname}' SUDAH ADA");
            // throw new Exception("DATA DENGAN CONTENT '{$request->content}' DAN MODELNAME '{$request->modelname}' SUDAH ADA");
        }
        
        return parent::store($request);
    }
}
