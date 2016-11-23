<?php

namespace App\Http\Requests;

use Gate;
use App\Http\Requests\Request;

class EditEventRequest extends Request {
	public function authorize() {
		return Gate::allows('admin');
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
