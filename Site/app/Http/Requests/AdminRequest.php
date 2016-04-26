<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdminRequest extends Request {
	public function authorize() {
		if(session()->get('authenticated_admin') != "true") {
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
