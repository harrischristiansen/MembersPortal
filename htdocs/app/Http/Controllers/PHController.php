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
		$passwordMD5 = md5($password);
		
		if($email == "") {
			if($password == env('ADMIN_PASS')) {
				$request->session()->put('authenticated_member', 'true');
				$request->session()->put('authenticated_admin', 'true');
			} else {
				$request->session()->flash('msg', 'Please enter an email.');
				return $this->getLogin();
			}
		} else {
			$matchingMembers = Member::where('email',$email)->orWhere('email_public', $email)->orWhere('email_purdue', $email)->get();
			
			if(count($matchingMembers) == 0) {
				$request->session()->flash('msg', 'No account was found with that email.');
				return $this->getLogin();
			}
			
			foreach($matchingMembers as $member) {
				if($member->password == $passwordMD5) {
					$this->setAuthenticated($request, $member->id, $member->name);
					return $this->getIndex();
				}
			}
			
			// If gets here, no account matched password
			$request->session()->flash('msg', 'Invalid password.');
			return $this->getLogin();
		}

		return $this->getLogin();
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
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
		
		// Create Member
		$member = new Member;
		$member->name = $memberName;
		$member->email = $email;
		$member->password = md5($password);
		if(strpos($email, "@purdue.edu") !== false) {
			$member->email_purdue = $email;
		}
		$member->graduation_year = $gradYear;
		$member->save();
		
		// Authenticate Application
		$this->setAuthenticated($request, $member->id, $member->name);
		
		return $this->getIndex();
	}
	
	public function setAuthenticated(Request $request, $memberID, $memberName) {
		$request->session()->put('authenticated_member', 'true');
		$request->session()->put('member_id', $memberID);
		$request->session()->put('member_name', $memberName);
		$request->session()->flash('msg', 'Logged In!');
	}
	
	/////////////////////////////// Resource Pages ///////////////////////////////
    
    public function getAnvilWifi() {
		return view('pages.anvilWifi');
	}
	
	/////////////////////////////// Viewing Members ///////////////////////////////
	
	public function getMembers() {
		$members = Member::all();
		
		return view('pages.members',compact("members"));
	}
	
	public function getMember(Request $request, $memberID) {
		$member = Member::find($memberID);
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		
		return $member->name;
	}
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	public function postMember(LoggedInRequest $request, $memberID) {
		$authenticate_id = $request->session()->get('member_id');
		$isAdmin = $request->session()->get('authenticated_admin');
		
		$member = Member::find($memberID);
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		
		if($memberID != $authenticated_id && $isAdmin != "true") {
			$request->session()->flash('msg', 'Error: Permission Denied.');
			return $this->getMember($request, $memberID);
		}
	}
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	public function getEvents() {
		$events = Event::all();
		
		return view('pages.events',compact("events"));
	}
	
	public function getEvent(Request $request, $eventID) {
		$isAdmin = $request->session()->get('authenticated_admin');
		
		$event = Event::find($eventID);
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		return $event->name;
	}
	
	/////////////////////////////// Managing Events ///////////////////////////////
	
	public function postEvent(AdminRequest $request, $eventID) {
		$isAdmin = $request->session()->get('authenticated_admin');
		
		if($eventID >= 0) {
			$event = Event::find($eventID);
		} else {
			$event = new Event;
		}
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		$event->name = $request->input("eventName");
		$event->time = $request->input("date");
		$event->location = $request->input("location");
		$event->facebook = $request->input("facebook");
		$event->save();
		
		if($eventID >= 0) {
			$request->session()->flash('msg', 'Event Updated!');
		} else {
			$request->session()->flash('msg', 'Event Created!');
		}
		return $this->getEvent($request, $eventID);
	}
	
	public function getEventNew() {
		return view('pages.event_new');
	}

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
