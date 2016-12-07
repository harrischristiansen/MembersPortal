<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AdminRequest;

use App\Models\Event;
use App\Models\Major;
use App\Models\Member;

class ReportsController extends BaseController {
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function getMembers(AdminRequest $request) {
		$members = Member::orderBy('created_at')->get();
		$events = Event::where('privateEvent',false)->orderBy('event_time')->get();
		
		// Join Dates
		$joinDates = $this->graphDataJoinDates($members);
		
		// Event Attendance
		$eventAttendanceData = $this->graphDataEventAttendance($members);
		
		// # Events Attended
		$numAttendedData = $this->graphDataNumAttended($members);
		
		// Member Graduation Year
		$memberYears = $this->graphDataMemberYears($members);
		
		// Major
		$majorsData = $this->graphDataMajor($members);
		
		return view('pages.members-graphs',compact("members","joinDates","eventAttendanceData","numAttendedData","memberYears","majorsData"));
	}
		
	public function getEvent(AdminRequest $request, $eventID) {
		$event = Event::findOrFail($eventID);
		
		$members = $event->members;
		if (count($members) == 0) {
			$members = $event->getAppliedMembers();
		}
		
		// Join Dates
		$joinDates = $this->graphDataJoinDates($members);
		
		// Event Attendance
		$eventAttendanceData = $this->graphDataEventAttendance($members);
		
		// # Events Attended
		$numAttendedData = $this->graphDataNumAttended($members);
		
		// Member Graduation Year
		$memberYears = $this->graphDataMemberYears($members);
		
		// Major
		$majorsData = $this->graphDataMajor($members);
		
		return view('pages.event-graphs', compact("event","joinDates","eventAttendanceData","numAttendedData","memberYears","majorsData"));
	}
	
	public function getEventBook(AdminRequest $request, $eventID) {
		$event = Event::findOrFail($eventID);
		
		$members = $event->members;
		$members_book = [];
		
		foreach ($members as $member) { // Pre-calculate names of users who checked student in
			$members_book[$member->name]["email"] = $member->email;
			if ($member->resume) {
				$members_book[$member->name]["resume"] = $request->root().$member->resumePath();
			}
		}
		
		return stripslashes(json_encode($members_book));
	}

	/////////////////////////////// Graph Data Processing Functions ///////////////////////////////
    
    public function graphDataJoinDates($members) {
	    $joinDatesDict = [];
	    $start = Member::orderBy('created_at')->first()->created_at;
		$end = Carbon::now()->modify('+1 day');
		for ($i = $start; $i < $end; $i->modify('+1 day')) {
			$joinDatesDict[$i->toDateString()] = 0;
		}
		foreach ($members as $member) {
			$dateString = $member->created_at->toDateString();
			$joinDatesDict[$dateString]++;
		}
		$joinDates = [];
		foreach ($joinDatesDict as $date=>$count) {
			array_push($joinDates, compact("date","count"));
		}
		
		return $joinDates;
    }
    
    public function graphDataEventAttendance($members) {
	    // Set to Correct Date Range
	    $datesDict = [];
	    $start = Member::orderBy('created_at')->first()->created_at;
	    $datesDict[$start->toDateString()] = 0;
		$end = Carbon::now()->modify('+1 day');
	    $datesDict[$end->toDateString()] = 0;
	    
	    // Sum Public Event Attendance
		foreach ($members as $member) {
			$events = $member->events()->where('privateEvent',false)->get();
			foreach ($events as $event) {
				$dateString = $event->event_time->toDateString();
				$datesDict[$dateString] = isset($datesDict[$dateString]) ? $datesDict[$dateString]+1 : 1;
			}
		}
		
		// Sort and Format Data
		ksort($datesDict);
		$datesFormated = [];
		foreach ($datesDict as $date=>$count) {
			array_push($datesFormated, compact("date","count"));
		}
		
		return $datesFormated;
    }
    
    public function graphDataNumAttended($members) {
	    $numAttendedDict = [];
		foreach ($members as $member) {
			$numAttended = $member->publicEventCount();
			$numAttendedDict[$numAttended] = isset($numAttendedDict[$numAttended]) ? $numAttendedDict[$numAttended]+1 : 1;
		}
		$numAttended = [];
		ksort($numAttendedDict);
		foreach ($numAttendedDict as $key=>$count) {
			array_push($numAttended, compact("key","count"));
		}
		
		return $numAttended;
    }
    
	public function graphDataMemberYears($members) {
		$memberYearsDict = [];
		foreach ($members as $member) {
			$memberYear = $member->graduation_year;
			$memberYearsDict[$memberYear] = isset($memberYearsDict[$memberYear]) ? $memberYearsDict[$memberYear]+1 : 1;
		}
		$memberYears = [];
		ksort($memberYearsDict);
		foreach ($memberYearsDict as $key=>$count) {
			array_push($memberYears, compact("key","count"));
		}
		
		return $memberYears;
	}
    
    public function graphDataMajor($members) {
	    $majors = Major::all();
		$majorsDict = [];
		foreach ($majors as $major) {
			$majorsDict[$major->name] = 0;
		}
		foreach ($members as $member) {
			if(isset($member->major)) {
				$majorsDict[$member->major->name]++;
			}
		}
		$majorsData = [];
		foreach ($majorsDict as $key=>$count) {
			$key = preg_replace('~\b(\w)|.~', '$1', $key); // Create 2 Character Abbreviateion
			array_push($majorsData, compact("key","count"));
		}
		
		return $majorsData;
    }
    
}