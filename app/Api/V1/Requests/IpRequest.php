<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class IpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ip' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
