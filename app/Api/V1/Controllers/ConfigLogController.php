<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Api\V1\Requests\ConfiglogRequest;

class ConfigLogController extends Controller
{
    public function store(ConfiglogRequest $request) {

        $activity = new Activity;
        $activity->text = $request->nik . " with birthdate = ". $request->date;
        $activity->value = $request->configvalue;
        $activity->ip_address = $request->ip();
        $result = $activity->save();

        return [
            'success' => $result,
            'message' => "date saved!"
        ];
    }
}