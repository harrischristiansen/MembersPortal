<?php

namespace App\Http\Requests;

use Gate;

class AdminRequest extends Request
{
    public function authorize()
    {
        return Gate::allows('permission', 'admin');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
