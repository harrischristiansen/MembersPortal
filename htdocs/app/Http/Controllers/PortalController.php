<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\EditMemberRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditEventRequest;

use App\Models\Application;
use App\Models\Event;
use App\Models\Location;
use App\Models\LocationRecord;
use App\Models\Major;
use App\Models\Member;

class PortalController extends Controller {
	
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
			$request->session()->flash('msg', 'Please enter an email.');
			return $this->getLogin();
		} else {
			$matchingMembers = Member::where('email',$email)->orWhere('email_public', $email)->orWhere('email_edu', $email)->get();
			
			if(count($matchingMembers) == 0) {
				$request->session()->flash('msg', 'No account was found with that email.');
				return $this->getLogin();
			}
			
			foreach($matchingMembers as $member) {
				if($member->password == $passwordMD5) {
					$this->setAuthenticated($request, $member->id, $member->name);
					
					// Admin
					if ($member->admin) {
						$request->session()->put('authenticated_admin', 'true');
					}
					
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
	
	public function postJoin(RegisterRequest $request) { // POST Register
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
			$request->session()->flash('msg', 'An account already exists with that email. Please use your '.env('DB_ORG_NAME').' account password if you have one.');
			return $this->getLogin();
		}
		
		// Create Member
		$member = new Member;
		$member->name = $memberName;
		$member->email = $email;
		$member->password = md5($password);
		if(strpos($email, ".edu") !== false) {
			$member->email_edu = $email;
		}
		$member->graduation_year = $gradYear;
		$member->save();
		
		// Authenticate Application
		$this->setAuthenticated($request, $member->id, $member->name);
		
		return $this->getIndex();
	}
	
	public function isAuthenticated($request) {
		return $request->session()->get('authenticated_member') == "true";
	}
	
	public function getAuthenticated($request) {
		if ($this->isAuthenticated($request)) {
			return Member::find($this->getAuthenticatedID($request));
		}
		return null;
	}
	
	public function getAuthenticatedID($request) {
		if ($this->isAuthenticated($request)) {
			return $request->session()->get('member_id');
		}
		return null;
	}
	
	public function setAuthenticated(Request $request, $memberID, $memberName) {
		$request->session()->put('authenticated_member', 'true');
		$request->session()->put('member_id', $memberID);
		$request->session()->put('member_name', $memberName);
		$request->session()->flash('msg', "Welcome $memberName!");
	}
	
	/////////////////////////////// Resource Pages ///////////////////////////////
    
    public function getAnvilWifi() {
		return view('pages.anvilWifi');
	}
	
	/////////////////////////////// Viewing Members ///////////////////////////////
	
	public function getMembers() {
		$members = Member::orderBy('name')->get();
		return view('pages.members',compact("members"));
	}
	
	public function getMembersAutocomplete(AdminRequest $request) {
		$requestTerm = $request->input('term');

		$searchFor = "%".$requestTerm.'%';
		$members = Member::where('name','LIKE',$searchFor)->orWhere('email','LIKE',$searchFor)->orWhere('email_public','LIKE',$searchFor)->orWhere('email_edu','LIKE',$searchFor)->orWhere('description','LIKE',$searchFor);
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
		
		$locations = $member->locations()->get();
		$events = $member->events()->get();
		$majors = Major::orderByRaw('(id = 1) DESC, name')->get(); // Order by name, but keep first major at top
		
		return view('pages.member',compact("member","locations","events","majors"));
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
		
		$locations = $member->locations()->get();
		$events = $member->events()->get();
		$setPassword = true;
		
		return view('pages.member',compact("member","locations","events","setPassword","reset_token"));
	}
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	public function postMember(EditMemberRequest $request, $memberID) {
		$member = Member::find($memberID);
		
		$memberName = $request->input('memberName');
		$password = $request->input('password');
		$email = $request->input('email');
		$email_public = $request->input('email_public');
		$gradYear = $request->input('gradYear');
		$gender = $request->input('gender');
		$major = $request->input('major');
		$description = $request->input('description');
		$facebook = $request->input('facebook');
		$github = $request->input('github');
		$linkedin = $request->input('linkedin');
		$devpost = $request->input('devpost');
		$website = $request->input('website');
		
		// Verify Input
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		if($email != $member->email && Member::where('email',$email)->first()) {
			$request->session()->flash('msg', 'An account already exists with that email.');
			return $this->getMember($request, $memberID);
		}
		
		//// Edit Member ////
		$member->name = $memberName;
		
		// Password
		if(strlen($password) > 0) {
			$member->password = md5($password);
			$this->setAuthenticated($request,$member->id,$member->name);
		}
		
		// Email
		$member->email = $email;
		if(strpos($email, ".edu") !== false) {
			$member->email_edu = $email;
		}
		$member->email_public = $email_public;
		if(strpos($email_public, ".edu") !== false) {
			$member->email_edu = $email_public;
		}
		
		// Text Fields
		$member->graduation_year = $gradYear;
		$member->gender = $gender;
		if ($major > 0) {
			$member->major_id = $major;
		}
		$member->description = $description;
		$member->facebook = $facebook;
		$member->github = $github;
		$member->linkedin = $linkedin;
		$member->devpost = $devpost;
		$member->website = $website;
		
		// Picture
		if ($request->hasFile('picture')) {
			$picture = $request->file('picture');
			if ($picture->isValid() && (strtolower($picture->getClientOriginalExtension())=="jpg" ||
			  strtolower($picture->getClientOriginalExtension())=="png") && (strtolower($picture->getClientMimeType())=="image/jpeg" ||
			  strtolower($picture->getClientMimeType())=="image/jpg" || strtolower($picture->getClientMimeType())=="image/png")) {
				$fileName = $picture->getClientOriginalName();
				$uploadPath = 'uploads/member_pictures/'; // base_path().'/public/uploads/member_pictures/
				$fileName_disk = $member->id."_".substr(md5($fileName), -6).".".$picture->getClientOriginalExtension();
				$picture->move($uploadPath, $fileName_disk);
				$member->picture = $fileName;
			}
		}
		
		// Resume
		if ($request->hasFile('resume')) {
			$resume = $request->file('resume');
			if ($resume->isValid() && strtolower($resume->getClientOriginalExtension())=="pdf" && strtolower($resume->getClientMimeType())=="application/pdf") {
				$fileName = $resume->getClientOriginalName();
				$uploadPath = 'uploads/resumes/'; // base_path().'/public/uploads/resumes/
				$fileName_disk = $member->id."_".substr(md5($fileName), -6).".".$resume->getClientOriginalExtension();
				$resume->move($uploadPath, $fileName_disk);
				$member->resume = $fileName;
			}
		}
		
		
		$member->save();
		
		// Return Response
		$request->session()->flash('msg', 'Profile Saved!');
		return $this->getMember($request, $memberID);
	}
	
	
	/////////////////////////////// Viewing Locations ///////////////////////////////
	
	public function getLocations() {
		$locations = Location::all();
		return view('pages.locations',compact("locations"));
	}
	
	public function getMap() {
		$locations = Location::all();
		return view('pages.map',compact("locations"));
	}
	
	public function getMapData() {
		$locations = Location::all();
		for($i=0;$i<count($locations);$i++) {
			$locations[$i]['members'] = $locations[$i]->members()->count();
		}
		return $locations;
	}
	
	public function getLocationsAutocomplete(LoggedInRequest $request) {
		$requestTerm = $request->input('term');

		$searchFor = "%".$requestTerm.'%';
		$locations = Location::where('name','LIKE',$searchFor);
		$results = $locations->get();
		
		for($i=0;$i<count($results);$i++) {
			$results[$i]['value'] = $results[$i]['name'];
		}

		return $results;
	}
	
	public function getCitiesAutocomplete(LoggedInRequest $request) {
		$requestTerm = $request->input('term');

		$searchFor = "%".$requestTerm.'%';
		$locations = Location::where('city','LIKE',$searchFor);
		$results = $locations->get();
		
		for($i=0;$i<count($results);$i++) {
			$results[$i]['value'] = $results[$i]['city'];
		}

		return $results;
	}
	
	public function getLocation($locationID) {
		$location = Location::find($locationID);
		
		if(is_null($location)) {
			$request->session()->flash('msg', 'Error: Location Not Found.');
			return $this->getLocations();
		}
		
		$members = $location->members()->get();
		
		return view('pages.location',compact("location","members"));
	}
	
	/////////////////////////////// Editing Locations ///////////////////////////////
	
/*
	public function postLocation(AdminRequest $request, $locationID) {
		$location = Location::find($locationID);
		
		if(is_null($location)) {
			$request->session()->flash('msg', 'Error: Location Not Found.');
			return $this->getLocations();
		}
		
		$location->name = $request->input('locationName');
		$location->city = $request->input('city');
		$location->save();
		
		return $this->getLocation($locationID);
	}
*/
	
	public function postLocationRecordNew(LoggedInRequest $request, $memberID) {
		$locationName = $request->input("locationName");
		$city = $request->input("city");
		$date_start = $request->input("date_start");
		$date_end = $request->input("date_end");
		
		$member = Member::find($memberID);
		$authenticated_id = $request->session()->get('member_id');
		
		if(is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		if($request->session()->get('authenticated_admin') != "true" && $memberID!=$authenticated_id) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return $this->getMembers();
		}
		
		$location = Location::firstOrCreate(['name'=>$locationName, 'city'=>$city]);
		
		if($location->loc_lat==0) {
			$this->completeLocation($location);
		}
		
		$locationRecord = new LocationRecord;
		$locationRecord->member_id = $memberID;
		$locationRecord->location_id = $location->id;
		$locationRecord->date_start = new Carbon($date_start);
		$locationRecord->date_end = new Carbon($date_end);
		$locationRecord->save();
		
		$request->session()->flash('msg', 'Location Record Added!');
		return $this->getMember($request, $memberID);
	}
	
	public function completeLocation($location) {
		//$location->loc_lat = rand(0, 90);
		//$location->loc_lng = rand(0, 360);
		
		// Get Correct Latitude / Longitude of Location from Google Places API
		$requestQuery = htmlentities(urlencode($location->name." ".$location->city));
		$requestResult = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$requestQuery.'&key='.env('KEY_GOOGLESERVER')), true);
		
		if (count($requestResult["results"]) > 0) {
			$location->loc_lat = $requestResult["results"][0]["geometry"]["location"]["lat"];
			$location->loc_lng = $requestResult["results"][0]["geometry"]["location"]["lng"];
		}
		
		$location->save();
	}
	
	public function getLocationRecordDelete(LoggedInRequest $request, $recordID) {
		$locationRecord = LocationRecord::find($recordID);
		$authenticated_id = $request->session()->get('member_id');
		
		if(is_null($locationRecord)) {
			$request->session()->flash('msg', 'Error: Location Record not Found.');
			return $this->getMembers();
		}
		if($request->session()->get('authenticated_member') != "true" && $locationRecord->member->id != $authenticated_id) {
			$request->session()->flash('msg', 'Error: Location Record not Found.');
			return $this->getMembers();
		}
		
		$return_memberID = $locationRecord->member->id;
		$locationRecord->delete();
		
		return redirect()->action('PortalController@getMember', [$return_memberID])->with('msg', 'Location Record Deleted!');
	}
	
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	public function getEvents() {
		$events = Event::all();
		$checkin = false;
		return view('pages.events',compact("events","checkin"));
	}
	
	public function getEvent(Request $request, $eventID) {
		$event = Event::find($eventID);
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents();
		}
		
		$members = $event->members()->get();
		
		$canApply = $this->isAuthenticated($request) && $event->requiresApplication;
		$canRegister = $this->isAuthenticated($request) && $event->requiresRegistration;
		$authenticatedMember = $this->getAuthenticated($request);
		if ($authenticatedMember != null) {
			$hasRegistered = count($authenticatedMember->applications()->where('event_id',$eventID)->get()) > 0;
		}
		
		return view('pages.event',compact("event","members","canApply","canRegister","hasRegistered"));
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
			return redirect()->action('PortalController@getEvent', [$event->id])->with('msg', 'Event Created!');
		}
	}
	
	public function getEventNew() {
		return view('pages.event_new');
	}
	
	public function getEventDelete($eventID) {
		Event::findOrFail($eventID)->delete();
		return $this->getEvents();
	}
	
	/////////////////////////////// Applications ///////////////////////////////
	
	public function getApply(Request $request, $eventID=-1) { // GET Apply
		$event = Event::findOrFail($eventID);
		$authenticatedMember = $this->getAuthenticated($request);
		if (!$authenticatedMember) { $authenticatedMember = new Member(); }
		$majors = Major::orderByRaw('(id = 1) DESC, name')->get(); // Order by name, but keep first major at top
		
		return view('pages.apply',compact('event', 'authenticatedMember', 'majors'));
	}
	
	public function postApply(LoggedInRequest $request, $eventID) { // POST Apply
		// Member Details
		$gender = $request->input('gender');
		$major = $request->input('major');
		
		$member = $this->getAuthenticated($request);
		$member->gender = $gender;
		$member->major_id = $major;
		$member->save();
		
		// Application Details
		$tshirt = $request->input('tshirt');
		$interests = $request->input('interests');
		$dietary = $request->input('dietary');
		
		$application = new Application();
		$application->member_id = $this->getAuthenticatedID($request);
		$application->event_id = $eventID;
		$application->tshirt = $tshirt;
		$application->interests = $interests;
		$application->dietary = $dietary;
		$application->save();
		
		$request->session()->flash('msg', 'Success, your application has been submitted!');
		return $this->getEvent($request, $eventID);
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
