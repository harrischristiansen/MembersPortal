<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Member;

class EditMemberRequest extends Request {
	public function authorize() {
		if(session()->get('authenticated_admin') == "true") { // Admin
			return true;
		}
		
		$requestID = str_replace(["member/","members/","/"],["","",""],request()->path());
		$reset_token = request()->input("reset_token");
		
		if(session()->get('authenticated_member') == "true") { // Logged In, Only Allow User To Modify Self
			$authenticated_id = session()->get('member_id');
			if ($requestID == $authenticated_id) {
				return true;
			}
			$member = Member::where('username',$requestID)->first();
			if ($member != Null && $member->id == $authenticated_id) {
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
