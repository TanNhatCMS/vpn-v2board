<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServerTrojanUpdate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'show' => 'in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'show.in' => '显示状态格式不正确',
        ];
    }
}
