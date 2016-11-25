<?php

namespace App\Http\Requests;

use Gate;
use App\Http\Requests\Request;

class EventRequest extends Request {
	public function authorize() {
		return Gate::allows('permission','events');
	}
	public function rules() {
		return [
			//
		];
	}
}
