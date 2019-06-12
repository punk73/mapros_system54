<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class ConfiglogRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nik'  => 'required',
            'date'       => 'required|date',
            'configvalue' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
