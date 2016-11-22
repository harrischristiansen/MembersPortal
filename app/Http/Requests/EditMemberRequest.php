<?php

namespace App\Http\Requests;

use Auth;
use App\Http\Requests\Request;
use App\Models\Member;

class EditMemberRequest extends Request {
	public function authorize() {
		if (Auth::check() && Auth::user()->admin) { // Admin
			return true;
		}
		
		$requestID = str_replace(["member/","members/","/"],["","",""],request()->path());
		$reset_token = request()->input("reset_token");
		
		if (Auth::check()) { // Logged In, Only Allow User To Modify Self
			$member = Auth::user();
			if ($requestID == $member->id || $requestID == $member->username) {
				return true;
			}
		} else { // Logged Out, Require Auth Token
			$member = Member::find($requestID);
			if ($member == Null) {
				$member = Member::where("username",$requestID)->firstOrFail();
			}
			
			if($reset_token == $member->reset_token()) {
				return true;
			}
		}
		return false;
	}
	public function rules() {
		return [
			'memberName' => 'required|max:255',
			'username' => 'alpha_num|max:255',
			'email' => 'required|email',
			'email_public' => 'email',
			'confirmPassword' => 'same:password',
			'gradYear' => 'required|integer|min:1900|max:2200',
		];
	}
}
