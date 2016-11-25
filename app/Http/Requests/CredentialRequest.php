<?php

namespace App\Http\Requests;

use Gate;
use App\Http\Requests\Request;

class CredentialRequest extends Request {
	public function authorize() {
		return Gate::allows('permission','credentials');
	}
	public function rules() {
		return [
			//
		];
	}
}
