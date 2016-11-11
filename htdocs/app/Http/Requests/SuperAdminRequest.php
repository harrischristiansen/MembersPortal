<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SuperAdminRequest extends Request {
	public function authorize() {
		if(session()->get('authenticated_superAdmin') != "true") {
			return false;
		}
		return true;
	}
	public function rules() {
		return [
			//
		];
	}
}
