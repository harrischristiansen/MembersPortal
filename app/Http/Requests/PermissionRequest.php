<?php

namespace App\Http\Requests;

use Gate;
use App\Http\Requests\Request;

class PermissionRequest extends Request {
	public function authorize() {
		return Gate::allows('permission','permissions');
	}
	public function rules() {
		return [
			//
		];
	}
}
