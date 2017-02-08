<?php

namespace App\Http\Requests;

use Gate;

class EventRequest extends Request
{
    public function authorize()
    {
        return Gate::allows('permission', 'events');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
