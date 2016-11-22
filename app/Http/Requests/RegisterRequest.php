<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterRequest extends Request {
	public function authorize() {
		return true;
	}
	public function rules() {
		return [
			'memberName' => 'max:255',
			'email' => 'required_with:memberName,email',
			'password_confirmation' => 'required_with:memberName,same:password',
			'gradYear' => 'required|integer',
		];
	}
}
