<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Api\V1\Requests\ConfiglogRequest;
use Dingo\Blueprint\Annotation\Request;

class ConfigLogController extends Controller
{
    public function index(Request $request) {
        $setting = setting('admin');
        // return dd($setting);
        $res = [];
        foreach ($setting as $key => $value) {
            # code...
            // return $value;
            $res[$key] = \is_numeric($value) ? (int) $value : $value;
        }

        return $res;
    }

    public function store(ConfiglogRequest $request) {

        $activity = new Activity;
        $activity->text = $request->nik . " Changes the config ";
        $activity->value = $request->configvalue;
        $activity->ip_address = $request->ip();
        $result = $activity->save();

        return [
            'success' => $result,
            'message' => "date saved!"
        ];
    }
}