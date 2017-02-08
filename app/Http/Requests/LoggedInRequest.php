<?php

namespace App\Http\Requests;

use Auth;

class LoggedInRequest extends Request
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
