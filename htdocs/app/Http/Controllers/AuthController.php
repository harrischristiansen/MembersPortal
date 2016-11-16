<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	File Created: Nov 2016
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;

use App\Http\Requests;
use App\Models\Member;

class AuthController extends BaseController {
	
	/////////////////////////////// Account Login ///////////////////////////////
	
	public function getLogin() {
		return view('pages.login');
	}

	public function postLogin(Request $request) {
		$email = $request->input('email');
		$password = $request->input('password');
		$passwordMD5 = md5($password);
		
		if($email == "") {
			$request->session()->flash('msg', 'Please enter an email.');
			return $this->getLogin();
		} else {
			$matchingMembers = Member::where('email',$email)->orWhere('email_public', $email)->orWhere('email_edu', $email)->get();
			
			if(count($matchingMembers) == 0) {
				$request->session()->flash('msg', 'No account was found with that email.');
				return $this->getLogin();
			}
			
			foreach($matchingMembers as $member) {
				if(Hash::check($password, $member->password) || $member->password == $passwordMD5) {
					$this->setAuthenticated($request, $member->id, $member->name);
					
					if (Hash::needsRehash($member->password)) { // Check If Password Needs Rehash
						$member->password = Hash::make($password);
					}
					
					$member->authenticated_at = Carbon::now();
					$member->timestamps = false; // Don't update timestamps
					$member->save();
					
					if ($member->admin) { // Admin Accounts
						$request->session()->put('authenticated_admin', 'true');
					}
					if ($member->superAdmin) { // SuperAdmin Accounts
						$request->session()->put('authenticated_superAdmin', 'true');
					}
					
					return $this->getIndex($request);
				}
			}
			
			// If gets here, no account matched password
			$request->session()->flash('msg', 'Invalid password.');
			return $this->getLogin();
		}

		return $this->getLogin();
	}
	
	/////////////////////////////// Account Logout ///////////////////////////////

	public function getLogout(Request $request) {
		$request->session()->put('member_id',"");
		$request->session()->put('member_name',"");
		$request->session()->put('authenticated_member', 'false');
		$request->session()->put('authenticated_admin', 'false');
		$request->session()->put('authenticated_superAdmin', 'false');
		
		$request->session()->flash('msg', 'You are now logged out');
		return $this->getIndex($request);
	}
	
	
	
	/////////////////////////////// Account Register ///////////////////////////////
	
	public function getJoin() {
		return view('pages.register');
	}
	
	public function postJoin(RegisterRequest $request) {
		$memberName = $request->input('memberName');
		$email = $request->input('email');
		$password = $request->input('password');
		$password_confirm = $request->input('confirmPassword');
		$gradYear = $request->input('gradYear');
		
		if($memberName=="" || $email=="" || $password=="" || $gradYear=="") {
			$request->session()->flash('msg', 'Please enter all fields.');
			return $this->getJoin();
		}
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$request->session()->flash('msg', 'Invalid Email Address.');
			return $this->getJoin();
		}
		
		if($password != $password_confirm) {
			$request->session()->flash('msg', 'Passwords did not match.');
			return $this->getJoin();
		}
		
		if(Member::where('email',$email)->first()) {
			$request->session()->flash('msg', 'An account already exists with that email. Please use your '.env('DB_ORG_NAME').' account password if you have one.');
			return $this->getLogin();
		}
		
		// Create Member
		$member = new Member;
		$member->name = $memberName;
		$member->email = $email;
		$member->password = Hash::make($password);
		if(strpos($email, ".edu") !== false) {
			$member->email_edu = $email;
		}
		$member->graduation_year = $gradYear;
		$member->save();
		
		// Authenticate Application
		$this->setAuthenticated($request, $member->id, $member->name);
		
		return $this->getIndex($request);
	}
	
	/////////////////////////////// Password Reset Request ///////////////////////////////
	
	public function getForgot(Request $request) {
		return view('pages.reset');
	}
	
	public function postForgot(Request $request) {
		$email = $request->input('email');
		
		$member = Member::where('email',$email)->first();
		
		if ($member == NULL) {
			$request->session()->flash('msg', 'No account was found with that email!');
			return $this->getForgot($request);
		}
		
		$this->emailResetRequest($member);
		
		$request->session()->flash('msg', 'A link to reset your password has been sent to your email!');
		return $this->getForgot($request);
	}
	
	public function emailResetRequest($member) {
		Mail::send('emails.resetRequest', ['member'=>$member], function ($message) use ($member) {
			$message->from('purduehackers@gmail.com', 'Purdue Hackers');
			$message->to($member->email);
			$message->subject("Reset your Purdue Hackers account password");
		});
	}
	
	/////////////////////////////// Password Reset Call ///////////////////////////////
	
	public function getReset(Request $request, $memberID, $reset_token) {
		// TODO: Get Member and append fields to view
		$member = Member::find($memberID);
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getIndex($request);
		}
		
		if($reset_token != $member->reset_token()) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getIndex($request);
		}
		
		$locations = $member->locations;
		$events = $member->events;
		$majors = Major::orderByRaw('(id = 1) DESC, name')->get(); // Order by name, but keep first major at top
		$setPassword = true;
		
		return view('pages.member',compact("member","locations","events","majors","setPassword","reset_token"));
	}
    
}