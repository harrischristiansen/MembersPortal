<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Member;

class EditMemberRequest extends Request {
	public function authorize() {
		if(session()->get('authenticated_admin') == "true") { // Admin
			return true;
		}
		
		$memberID = str_replace(["member","/"],["",""],request()->path());
		$reset_token = request()->input("reset_token");
		
		if(session()->get('authenticated_member') == "true") { // Logged In, Only Allow User To Modify Self
			$authenticated_id = session()->get('member_id');
			if($authenticated_id == $memberID) {
				return true;
			}
		} else { // Logged Out, Require Auth Token
			$member = Member::find($memberID);
			if($reset_token == $member->reset_token()) {
				return true;
			}
		}
		return false;
	}
	public function rules() {
		return [
			'memberName' => 'required|max:255',
			'email' => 'required|email',
			'email_public' => 'email',
			'confirmPassword' => 'same:password',
			'gradYear' => 'required|integer|min:1900|max:2200',
		];
	}
}
