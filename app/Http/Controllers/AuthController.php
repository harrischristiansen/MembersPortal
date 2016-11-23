<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	File Created: Nov 2016
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use App;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;

use App\Http\Requests;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\AdminRequest;
use App\Models\Member;

class AuthController extends BaseController {
	
	/////////////////////////////// Account Login ///////////////////////////////
	
	public function getLogin() {
		return view('pages.login');
	}

	public function postLogin(Request $request) {
		$email = $request->input('email');
		$password = $request->input('password');
		$remember = $request->input('remember');
		
		if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
			$member = Auth::user();
			$member->authenticated_at = Carbon::now();
			$member->timestamps = false; // Don't update timestamps
			$member->save();
			
			$request->session()->flash('msg', 'Welcome '.$member->name.'!');
            return redirect()->intended('');
        }
        
        // MD5 Backward Compatability
        $passwordMD5 = md5($password);
        $member = Member::where('email',$email)->where('password', $passwordMD5)->first();
        if ($member) {
			$member->authenticated_at = Carbon::now();
			$member->password = Hash::make($password);
			$member->timestamps = false; // Don't update timestamps
			$member->save();
			
			Auth::login($member);
			$request->session()->flash('msg', 'Welcome '.$member->name.'!');
            return redirect()->intended('');
        }
        
		$request->session()->flash('msg', 'Invalid username or password.');
		return $this->getLogin();
	}
	
	/////////////////////////////// Account Logout ///////////////////////////////

	public function getLogout(Request $request) {
		Auth::logout();
		
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
		$password_confirm = $request->input('password_confirmation');
		$gradYear = $request->input('gradYear');
		
		// Validate Input
		if ($memberName=="" || $email=="" || $password=="" || $gradYear=="") {
			$request->session()->flash('msg', 'Please enter all fields.');
			return $this->getJoin();
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$request->session()->flash('msg', 'Invalid Email Address.');
			return $this->getJoin();
		}
		
		if ($password != $password_confirm) {
			$request->session()->flash('msg', 'Passwords did not match.');
			return $this->getJoin();
		}
		
		if (Member::where('email',$email)->first()) {
			$request->session()->flash('msg', 'An account already exists with that email. Please use your '.env('DB_ORG_NAME').' account password if you have one.');
			return $this->getLogin();
		}
		
		// Create Account + Authenticate
		$member = $this->createAccount($memberName, $email, $password, $gradYear);
		Auth::login($member);
		
		$request->session()->flash('msg', 'Welcome '.$member->name.'!');
		return $this->getIndex($request);
	}
	
	public function createAccount($name, $email, $password, $gradYear) {
		// Create Member
		$member = new Member;
		$member->name = $name;
		$member->username = app('app\Http\Controllers\MemberController')->generateUsername($member);
		$member->email = $email;
		if (strlen($password) > 2) {
			$member->password = Hash::make($password);
		}
		if (strpos($email, ".edu") !== false) {
			$member->email_edu = $email;
		}
		$member->graduation_year = $gradYear;
		$member->save();
		
		return $member;
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
		if (App::environment('local', 'staging') && strpos($member->email, 'harrischristiansen.com') === false) {
			return false;
		}
		
		Mail::send('emails.resetRequest', ['member'=>$member], function ($message) use ($member) {
			$message->from('purduehackers@gmail.com', 'Purdue Hackers');
			$message->to($member->email);
			$message->subject("Reset your Purdue Hackers account password");
		});
	}
	
	/////////////////////////////// Perform Password Reset Form ///////////////////////////////
	
	public function getReset(Request $request, $memberID, $reset_token) {
		$member = Member::find($memberID);
		if ($reset_token != $member->reset_token()) {
			$request->session()->flash('msg', 'Error: Invalid Password Reset Link');
			return $this->getIndex($request);
		}
		
		Auth::login($member);
		
		$memberRequest = app('app\Http\Controllers\MemberController')->getMemberEdit($request, $memberID);
		$setPassword = true;
		return $memberRequest->with(compact("setPassword"));
	}
	
	/////////////////////////////// Account Setup Emails ///////////////////////////////
	
	public function getSetupAccountEmails(AdminRequest $request) { // Batch email all accounts that have not been setup, prompting them to setup.
		$members = Member::where('graduation_year',0)->get();
		
		$nowDate = Carbon::now();
		
		foreach ($members as $member) {
			if ($member->setupEmailSent->diffInDays($nowDate) > 30) {
				$this->emailAccountCreated($member, $member->events()->first());
			}
		}
		
		$request->session()->flash('msg', 'Success, setup account emails have been sent!');
		return $this->getIndex($request);
	}
    
}