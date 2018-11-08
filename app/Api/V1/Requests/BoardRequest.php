<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class BoardRequest extends FormRequest
{
    public function rules()
    {
        return [
            'board_id'  => 'required',
            'nik'       => 'required',
            'modelname' => 'required',
            'judge'     => 'required|in:OK,NG',
            'symptom'   => 'required_if:judge,NG|array',
            // 'critical_part' => '',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
