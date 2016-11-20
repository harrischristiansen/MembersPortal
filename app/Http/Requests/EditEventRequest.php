<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditEventRequest extends Request {
	public function authorize() {
		if(session()->get('authenticated_admin') == "true") {
			return true;
		}
		return false;
	}
	public function rules() {
		return [
			'eventName' => 'required|max:255',
			'date' => 'required|date',
			'hour' => 'required|between:-1,24',
			'minute' => 'required|between:-1,60',
			'location' => 'required',
			'facebook' => 'url',
		];
	}
}
