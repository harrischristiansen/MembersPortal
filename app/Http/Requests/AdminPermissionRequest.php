<?php

namespace App\Http\Requests;

use Gate;

class AdminPermissionRequest extends Request
{
    public function authorize()
    {
        return Gate::allows('permission', 'adminpermission');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
