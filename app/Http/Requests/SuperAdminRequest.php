<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SuperAdminRequest extends Request {
	public function authorize() {
		return Gate::allows('super-admin');
	}
	public function rules() {
		return [
			//
		];
	}
}
