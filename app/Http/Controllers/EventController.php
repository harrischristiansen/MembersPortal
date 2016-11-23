<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\EditEventRequest;
use App\Http\Requests\AdminRequest;
use App\Models\Application;
use App\Models\Event;
use App\Models\Major;
use App\Models\Member;

class EventController extends BaseController {
	public function __construct() {
		$this->middleware('auth', ['except' => [
            'getIndex','getEvent'
        ]]);
	}
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	public function getIndex(Request $request) {
		if (Gate::allows('admin')) {
			$events = Event::orderBy("event_time","desc")->get();
		} else {
			$events = Event::where('privateEvent',false)->orderBy("event_time","desc")->get();
		}
		return view('pages.events',compact("events"));
	}
	
	public function getEvent(Request $request, $eventID) {
		$event = Event::findOrFail($eventID);
		
		$members = $event->members;
		
		foreach ($members as $member) { // Pre-calculate names of users who checked student in
			$recorded_member = Member::find($member->events()->find($eventID)->pivot->recorded_by);
			$member->recorded_by = $recorded_member;
		}
		
		$requiresApplication = Auth::check() && $event->requiresApplication;
		$authenticatedMember = Auth::user();
		if ($authenticatedMember != null) {
			$hasRegistered = count($authenticatedMember->applications()->where('event_id',$eventID)->get()) > 0;
		}
		
		$applications = []; // Get list of applications (if admin)
		if (Gate::allows('admin')) {
			$applications = $event->applications;
		}
		
		return view('pages.event', compact("event","members","requiresApplication","hasRegistered","applications"));
	}
	
	public function getCreate(AdminRequest $request) {
		$event = new Event;
		$event->id = 0;
		$members = [];
		$requiresApplication = false;
		$hasRegistered = false;
		$applications = [];
		return view('pages.event', compact("event","members","requiresApplication","hasRegistered","applications"));
	}
	
	/////////////////////////////// Editing Events ///////////////////////////////
	
	public function postEvent(EditEventRequest $request, $eventID) {
		$eventName = $request->input("eventName");
		$eventPrivate = $request->input("privateEvent")=="true" ? true : false;
		$requiresApplication = $request->input("requiresApplication")=="true" ? true : false;
		$eventDate = $request->input("date");
		$eventHour = $request->input("hour");
		$eventMinute = $request->input("minute");
		$eventLocation = $request->input("location");
		$eventFB = $request->input("facebook");
		
		if($eventID == 0) {
			$event = new Event;
		} else {
			$event = Event::find($eventID);
		}
		
		// Verify Input
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents($request);
		}
		
		// Edit Event
		$event->name = $eventName;
		$event->privateEvent = $eventPrivate;
		$event->requiresApplication = $requiresApplication;
		$event->event_time = new Carbon($eventDate." ".$eventHour.":".$eventMinute);
		$event->location = $eventLocation;
		$event->facebook = $eventFB;
		$event->save();
		
		// Return Response
		if($eventID == 0) { // New Event
			return redirect()->action('EventController@getEvent', [$event->id])->with('msg', 'Event Created!');
		} else {
			$request->session()->flash('msg', 'Event Updated!');
			return $this->getEvent($request, $eventID);
		}
	}
	
	public function getDelete(AdminRequest $request, $eventID) {
		Event::findOrFail($eventID)->delete();
		
		return redirect()->action('EventController@getIndex')->with('msg', 'Event Deleted! If this was done by mistake, contact the site administrator to restore this event.');
	}
	
	/////////////////////////////////// Event Emails ///////////////////////////////////
	
	public function getMessage(AdminRequest $request, $eventID) {
		$event = Event::findOrFail($eventID);
		
		return view('pages.event-message', compact("event"));
	}
	
	public function postMessage(AdminRequest $request, $eventID) {
		$event = Event::findOrFail($eventID);
		
		$method = $request->input("method");
		$subject = $request->input("subject");
		$msg = nl2br(e($request->input("message")));
		$target = $request->input("target");
		
		// Get Recipient Members
		$members = null;
		if ($target == "all") {
			$members = Member::all();
		} elseif ($target == "both") {
			$members_att = $event->members;
			$members_reg = $event->getAppliedMembers();
			$members = $members_att->merge($members_reg)->all();
		} elseif ($target == "att") {
			$members = $event->members;
		} elseif ($target == "reg") {
			$members = $event->getAppliedMembers();
		} elseif ($target == "not") {
			$members_all = Member::all();
			$members_reg = $event->getAppliedMembers();
			$members = $members_all->diff($members_reg)->all();
		} else {
			$members = $event->members;
		}
		
		// Ensure message sent to site admin and admin who sent
		$members_mustReceive = collect([Auth::user(), Member::find(1)]);
		foreach ($members_mustReceive as $member) {
			if ($members->contains($member) == false) {
				$members->push($member);
			}
		}
		
		// Send Messages to Recipients
		foreach ($members as $member) {
			// Fill Placeholders
			$placeholder_values = [
				'{{name}}' => $member->name,
				'{{setpassword}}' => $member->reset_url(),
				'{{register}}' => $member->apply_url($event->id),
				'{{link}}' => '<a href="',
				'{{link-text}}' => '">',
				'{{/link}}' => '</a>',
			];
			$memberMsg = str_replace(array_keys($placeholder_values), array_values($placeholder_values), $msg);
			
			// Send Message
			if ($method == "email") { // Send Email
				if ($members_mustReceive->contains($member)) {
					$this->sendEmail($member, "COPY: ".$subject, $memberMsg."\n\n - Sent by ".$member->name);
				} else {
					$this->sendEmail($member, $subject, $memberMsg);
				}
			} elseif ($method == "sms") { // Send SMS
				if (strlen($member->phone) > 9) { // If valid #
					$this->sendSMS($member, $memberMsg);
				}
			}
		}
		
		return redirect()->action('EventController@getEvent', [$eventID])->with('msg', 'Success, message sent!');
	}
	
	/////////////////////////////// Event Checkin System ///////////////////////////////
	
	public function getCheckin(AdminRequest $request, $eventID) {
		$event = Event::find($eventID);
		
		if(is_null($event)) {
			$request->session()->flash('msg', 'Error: Event Not Found.');
			return $this->getEvents($request);
		}
		
		return view('pages.checkin',compact("event","eventID"));
	}
	
	public function getCheckinPhone(AdminRequest $request, $eventID) {
		$getCheckin = $this->getCheckin($request, $eventID);
		
		return $getCheckin->with('checkinPhone',true);
	}
	
	public function postCheckin(AdminRequest $request) {
		$successResult = "true";
		$memberName = $request->input("memberName");
		$memberEmail = $request->input("memberEmail");
		$memberPhone = $request->input("memberPhone");
		$event = Event::find($request->input("eventID"));
		
		if ($request->input("memberID") > 0) { // Search By memberID
			$member = Member::find($request->input("memberID"));
			if ($memberEmail != $member->email) {
				$member = null;
			}
		} else { $member = null; }
		
		if ($member == null) { // Search By Name
			$member = Member::where('name',$memberName)->where('email',$memberEmail)->first();
		}
		
		if (strlen($memberName)<2 || !filter_var($memberEmail, FILTER_VALIDATE_EMAIL)) { // Validate Input
			return "invalid";
		}
		
		if (is_null($event)) { // Verify Event Exists
			return "false";
		}
		
		if (is_null($member)) { // New Member
			$member = new Member;
			
			if (Member::where('email',$memberEmail)->first()) {
				return "exists";
			}
			
			$member->name = $memberName;
			$member->username = app('app\Http\Controllers\MemberController')->generateUsername($member);
			$member->email = $memberEmail;
			
			$member->save();
			$successResult = "new";
			$this->emailAccountCreated($member, $event);
		} else { // Existing Member, If account not setup, send creation email
			if ($member->graduation_year == 0) {
				$this->emailAccountCreated($member, $event);
			}
		}
		
		if ($event->members()->find($member->id)) { // Check if Repeat
			return "repeat";
		}
		
		$event->members()->attach($member->id,['recorded_by' => Auth::user()->id]); // Save Record
		
		if (strlen($memberPhone) > 9) {
			$member->phone = $memberPhone;
			$member->save();
		} elseif (strlen($memberPhone)>2) {
			return "phone";
		}
		
		return $successResult;
	}
	
	/////////////////////////////// Applications ///////////////////////////////
	
	public function getApply(Request $request, $eventID=-1) { // GET Apply
		$event = Event::findOrFail($eventID);
		$authenticatedMember = Auth::user();
		if (!$authenticatedMember) { $authenticatedMember = new Member(); }
		$majors = Major::orderByRaw('(id = 1) DESC, name')->get(); // Order by name, but keep first major at top
		
		if ($authenticatedMember != null) {
			$hasRegistered = count($authenticatedMember->applications()->where('event_id',$eventID)->get()) > 0;
		}
		
		return view('pages.apply',compact('event', 'authenticatedMember', 'majors', 'hasRegistered'));
	}
	
	public function getApplyAuth(Request $request, $eventID, $memberID, $reset_token) {
		$event = Event::findOrFail($eventID);
		$member = Member::findOrFail($memberID);
		
		if ($reset_token != $member->reset_token()) {
			$request->session()->flash('msg','Error: Invalid Authentication Token');
			return $this->getIndex($request);
		}
		
		Auth::login($member);
		
		return $this->getApply($request, $eventID);
	}
	
	public function getRegister(LoggedInRequest $request, $eventID) { // Submit Empty Application
		$event = Event::findOrFail($eventID);
		
		if ($event->requiresApplication) {
			$request->session()->flash('msg','Error: Invalid Authentication Token');
			return $this->getEvent($request, $eventID);
		}
		
		$member = Auth::user();
		
		if ($event->applications()->where('member_id',$member->id)->first()) {
			$request->session()->flash('msg','Error: You are already registered for '.$event->name.".");
			return $this->getEvent($request, $eventID);
		}
		
		$application = new Application();
		$application->member_id = $member->id;
		$application->event_id = $eventID;
		$application->save();
		
		$request->session()->flash('msg','Success: You are registered for '.$event->name.'!');
		return $this->getEvent($request, $eventID);
	}
	
	public function getUnregister(LoggedInRequest $request, $eventID) { // Delete Application
		$event = Event::findOrFail($eventID);
		
		$member = Auth::user();
		
		$event->applications()->where('member_id',$member->id)->first()->delete();
		
		$request->session()->flash('msg','You are no longer registered for '.$event->name.'.');
		return $this->getEvent($request, $eventID);
	}
	
	public function postApply(LoggedInRequest $request, $eventID) { // POST Apply
		// Member Details
		$gender = $request->input('gender');
		$major = $request->input('major');
		
		$member = Auth::user();
		$member->gender = $gender;
		$member->major_id = $major;
		$member->save();
		
		// Application Details
		$tshirt = $request->input('tshirt');
		$interests = $request->input('interests');
		$dietary = $request->input('dietary');
		
		$application = new Application();
		$application->member_id = $member->id;
		$application->event_id = $eventID;
		$application->tshirt = $tshirt;
		$application->interests = $interests;
		$application->dietary = $dietary;
		$application->save();
		
		$request->session()->flash('msg', 'Success, your application has been submitted!');
		return $this->getEvent($request, $eventID);
	}
	
	public function getApplications(AdminRequest $request, $eventID=-1) {
		$event = Event::findOrFail($eventID);
		$applications = $event->applications;
		
		return view('pages.applications',compact("event","applications"));
	}
	
	public function getApplicationsUpperclassmen(AdminRequest $request, $eventID=-1) {
		$event = Event::findOrFail($eventID);
		$members = $event->getAppliedMembers();
		$upperclassmen = [];
		foreach ($members as $member) {
			if ($member->graduation_year > 2016 && $member->graduation_year < 2020) {
				array_push($upperclassmen, $member);
			}
		}
		$upperclassmen = collect($upperclassmen)->pluck("email","name");
		
		return $upperclassmen;
	}
    
}