<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class AoiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'board_id' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
