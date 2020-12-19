<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as Controller;
use App\Sequence;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;

class SequenceController extends Controller
{
    public function showCopy($id) {
        $sequence = Sequence::select([
            'name',
            'modelname',
            'line_id',
            'pwbname',
            'process',
        ])
        ->find($id);

        return view('vendor.voyager.sequences.copy', ['sequence' => $sequence->toArray() ]);
    }

    public function store(Request $request){
        // 
        // return $request->all();
        $rule = [
            'modelname' => "required", 
            'pwbname' => "required",
            "line_id" => "required",
        ];
        $params = $this->validate($request, $rule);

        $alreadyExist = Sequence::where(function ($q) use ($request, $rule) {
            foreach($rule as $key => $value) {
                $q->where($key, $request->{$key});
            }
        })->first();

        if($alreadyExist) {
            $msg = "DATA DENGAN MODEL '{$request->modelname}', PWBNAME {$request->pwbname} DAN LINE {$request->line_id} SUDAH ADA. SILAHKAN EDIT SAJA.";

            if ($request->ajax()) {
                // return response()->json(['success' => true, 'message' => $msg]);
            }

            return redirect()
                ->route("voyager.sequences.index")
                ->with([
                    'message'    => $msg,
                    'alert-type' => 'error',
                ]);

        }

        return parent::store($request);
    }

    
}
