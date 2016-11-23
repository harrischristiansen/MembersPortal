<?php

namespace App\Http\Requests;

use Auth;
use App\Http\Requests\Request;

class AdminRequest extends Request {
	public function authorize() {
		return Gate::allows('admin');
	}
	public function rules() {
		return [
			//
		];
	}
}
