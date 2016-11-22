<?php

namespace App\Http\Requests;

use Auth;
use App\Http\Requests\Request;

class AdminRequest extends Request {
	public function authorize() {
		if(Auth::check() && Auth::user()->admin) {
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
