<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as Controller;
use App\Sequence;
use App\Weightcontrol;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;

class WeightcontrolController extends Controller
{
    public function store(Request $request)
    {
        // 
        // return $request->all();
        $rule = [
            'MODEL_NAME' => "required",
        ];
        $params = $this->validate($request, $rule);

        $request->merge([
            'MODEL_NAME' => \strtoupper($request->MODEL_NAME)
        ]);

        $alreadyExist = Weightcontrol::where(function ($q) use ($request, $rule) {
            foreach ($rule as $key => $value) {
                $q->where($key, $request->{$key});
            }
        })->first();

        if ($alreadyExist) {
            $msg = "DATA DENGAN MODEL '{$request->MODEL_NAME}' SUDAH ADA. SILAHKAN EDIT SAJA.";

            if ($request->ajax()) {
                // return response()->json(['success' => true, 'message' => $msg]);
            }

            return redirect()
                ->route("voyager.weight-control.index")
                ->with([
                    'message'    => $msg,
                    'alert-type' => 'error',
                ]);
        }

        return parent::store($request);
    }
}
