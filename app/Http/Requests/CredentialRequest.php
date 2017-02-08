<?php

namespace App\Http\Requests;

use Gate;

class CredentialRequest extends Request
{
    public function authorize()
    {
        return Gate::allows('permission', 'credentials');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
