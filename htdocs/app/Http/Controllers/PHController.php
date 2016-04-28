<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	For: Purdue Hackers - Membership Portal
*/

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\EditMemberRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\EditEventRequest;
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
				$request->session()->put('authenticated_admin', 'true');
				$request->session()->put('authenticated_member', 'true');
				$request->session()->put('member_id', '-1');
				$request->session()->put('member_name', 'Admin');
				$request->session()->flash('msg', 'Logged In: Admin!');
				return $this->getIndex();
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
		$request->session()->put('member_id',"");
		$request->session()->put('member_name',"");
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
	
	public function getMembersAutocomplete(AdminRequest $request) {
		$requestTerm = $request->input('term');

		$searchFor = "%".$requestTerm.'%';
		$members = Member::where('name','LIKE',$searchFor)->orWhere('email','LIKE',$searchFor)->orWhere('email_public','LIKE',$searchFor)->orWhere('email_purdue','LIKE',$searchFor)->orWhere('description','LIKE',$searchFor);
		$results = $members->get();
		
		for($i=0;$i<count($results);$i++) {
			$results[$i]['value'] = $results[$i]['name'];
			$results[$i]['attended'] = count($results[$i]->events()->get());
		}

		return $results;
	}
	
	public function getMember(Request $request, $memberID) {
		$member = Member::find($memberID);
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		
		$events = $member->events()->get();
		$reset_token_valid = $member->reset_token();
		
		return view('pages.member',compact("member","events","reset_token_valid"));
	}
	
	public function getReset(Request $request, $memberID, $reset_token) {
		$member = Member::find($memberID);
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getIndex();
		}
		
		if($reset_token != $member->reset_token()) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getIndex();
		}
		
		$events = $member->events()->get();
		$setPassword = true;
		
		return view('pages.member',compact("member","events","setPassword","reset_token"));
	}
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	public function postMember(EditMemberRequest $request, $memberID) {
		$member = Member::find($memberID);
		$memberName = $request->input('memberName');
		$password = $request->input('password');
		$email = $request->input('email');
		$email_public = $request->input('email_public');
		$description = $request->input('description');
		$gradYear = $request->input('gradYear');
		
		// Verify Input
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		if($email != $member->email && Member::where('email',$email)->first()) {
			$request->session()->flash('msg', 'An account already exists with that email.');
			return $this->getMember($request, $memberID);
		}
		
		// Edit Member
		$member->name = $memberName;
		$member->email = $email;
		if(strpos($email, "@purdue.edu") !== false) {
			$member->email_purdue = $email;
		}
		if(strlen($password) > 0) {
			$member->password = md5($password);
			$this->setAuthenticated($request,$member->id,$member->name);
		}
		$member->email_public = $email_public;
		if(strpos($email_public, "@purdue.edu") !== false) {
			$member->email_purdue = $email_public;
		}
		$member->description = $description;
		$member->graduation_year = $gradYear;
		$member->save();
		
		// Return Response
		$request->session()->flash('msg', 'Profile Saved!');
		return $this->getMember($request, $memberID);
	}
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	public function getEvents() {
		$events = Event::all();
		$checkin = false;
		return view('pages.events',compact("events","checkin"));
	}
	
	public function getEvent(Request $request, $eventID) {
		$isAdmin = $request->session()->get('authenticated_admin');
		$event = Event::find($eventID);
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		$members = $event->members()->get();
		
		return view('pages.event',compact("event","members"));
	}
	
	/////////////////////////////// Event Checkin System ///////////////////////////////
	
	public function getCheckinEvents(AdminRequest $request) {
		$events = Event::all();
		$checkin = true;
		return view('pages.events',compact("events","checkin"));
	}
	
	public function getCheckin(AdminRequest $request, $eventID) {
		$event = Event::find($eventID);
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		return view('pages.checkin',compact("event","eventID"));
	}
	
	public function getCheckinMember(AdminRequest $request, $eventID, $memberID) {
		$event = Event::find($eventID);
		$member = Member::find($memberID);
		
		if(is_null($event) || is_null($member)) {
			return "false";
		}
		
		if($event->members()->find($member->id)) {
			return "repeat";
		}
		$event->members()->attach($member->id);
		
		return "true";
	}
	
	/////////////////////////////// Managing Events ///////////////////////////////
	
	public function postEvent(EditEventRequest $request, $eventID) {
		$eventName = $request->input("eventName");
		$eventDate = $request->input("date");
		$eventHour = $request->input("hour");
		$eventMinute = $request->input("minute");
		$eventLocation = $request->input("location");
		$eventFB = $request->input("facebook");
		
		if($eventID >= 0) {
			$event = Event::find($eventID);
		} else {
			$event = new Event;
		}
		
		// Verify Input
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		// Edit Event
		$event->name = $eventName;
		$event->event_time = new Carbon($eventDate." ".$eventHour.":".$eventMinute);
		$event->location = $eventLocation;
		$event->facebook = $eventFB;
		$event->save();
		
		// Return Response
		if($eventID >= 0) {
			$request->session()->flash('msg', 'Event Updated!');
			return $this->getEvent($request, $eventID);
		} else { // New Event
			return redirect()->action('PHController@getEvent', [$event->id])->with('msg', 'Event Created!');
		}
	}
	
	public function getEventNew() {
		return view('pages.event_new');
	}
	
	public function getEventDelete($eventID) {
		Event::findOrFail($eventID)->delete();
		return $this->getEvents();
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
