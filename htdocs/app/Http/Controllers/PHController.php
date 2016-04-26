<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	For: Purdue Hackers - Membership Portal
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;

use App\Models\Member;
use App\Models\Event;

class PHController extends Controller {
	
	/////////////////////////////// Home ///////////////////////////////
    
    public function getIndex() {
		return view('pages.home');
	}
	
	/////////////////////////////// Authentication ///////////////////////////////
	
	public function getLogin() {
		return view('pages.login');
	}

	public function postLogin(Request $request) {
		$email = $request->input('email');
		$password = $request->input('password');
		
		if($email == "") {
			if($password == env('ADMIN_PASS')) {
				$request->session()->put('authenticated_member', 'true');
				$request->session()->put('authenticated_admin', 'true');
			} else {
			$request->session()->flash('msg', 'Please enter an email!');
			return $this->getLogin();
			}
		} else {
			$request->session()->put('authenticated_member', 'true');
		}

		return $this->getIndex();
	}

	public function getLogout(Request $request) {
		$request->session()->put('authenticated_member', 'false');
		$request->session()->put('authenticated_admin', 'false');

		return $this->getIndex();
	}
	
	public function getJoin() { // GET Register
		return view('pages.register');
	}
	
	public function postJoin(Request $request) { // POST Register
		$memberName = $request->input('memberName');
		$email = $request->input('email');
		$password = $request->input('password');
		$password_confirm = $request->input('confirmPassword');
		$gradYear = $request->input('gradYear');
		
		if($memberName=="" || $email=="" || $password=="" || $gradYear=="") {
			$request->session()->flash('msg', 'Please fill our all fields.');
			return $this->getJoin();
		}
		
		if(!filter_var($email_a, FILTER_VALIDATE_EMAIL)) {
			$request->session()->flash('msg', 'Invalid Email Address.');
			return $this->getJoin();
		}
		
		if($password != $password_confirm) {
			$request->session()->flash('msg', 'Passwords did not match.');
			return $this->getJoin();
		}
		
		if(Member::where('email',$email)->first()) {
			$request->session()->flash('msg', 'An account already exists with that email.');
			return $this->getJoin();
		}
		
		$member = new Member;
		$member->name = $memberName;
		$member->email = $email;
		$member->password = md5($password);
		if(strpos($email, "@purdue.edu") !== false) {
			$member->email_purdue = $email;
		}
		$member->graduation_year = $gradYear;
		$member->save();
		
		$request->session()->put('authenticated_member', 'true');
		
		return $this->getIndex();
	}
	
	/////////////////////////////// Resource Pages ///////////////////////////////
    
    public function getAnvilWifi() {
		return view('pages.anvilWifi');
	}
	
	/////////////////////////////// Viewing Members ///////////////////////////////
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	/////////////////////////////// Managing Events ///////////////////////////////

	/////////////////////////////// Helper Functions ///////////////////////////////
	
	public static function generateRandomInt() {
        srand();
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
