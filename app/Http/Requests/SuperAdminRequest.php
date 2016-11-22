<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SuperAdminRequest extends Request {
	public function authorize() {
		if(Auth::check() && Auth::user()->superAdmin) {
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
