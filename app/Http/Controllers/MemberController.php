<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Gate;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

use App\Http\Requests;
use App\Http\Requests\EditMemberRequest;

use App\Models\Major;
use App\Models\Member;

class MemberController extends BaseController {
	
	/////////////////////////////// Viewing Members ///////////////////////////////
	
	public function getIndex(Request $request) {
		$members = self::getMembersListCache();
		return view('pages.members',compact("members"));
	}

	public static function getMembersListCache() {
		return Cache::remember('members_list', 65, function () {
			return Member::with('events')->get()->sortBy(function($member, $key) {
				return sprintf('%04d',1000-$member->publicEventCount())."_".$member->name;
			});
		});
	}
	
	public function getMember(Request $request, $memberID) {
		$member = $this->findMember($memberID);
		
		if (is_null($member)) {
			$request->session()->flash('msg', 'Error: Page Not Found');
			return parent::getIndex($request);
		}
		
		$locations = $member->locations;
		$events = $member->events;
		
		return view('pages.member',compact("member","locations","events"));
	}
	
	public function getMemberEdit(Request $request, $memberID) {
		$member = $this->findMember($memberID);
		
		if (is_null($member)) {
			return redirect()->action('HomeController@getIndex')->with('msg', 'Error: Page Not Found');
		}
		
		$majors = Major::orderByRaw('(id = 1) DESC, name')->get(); // Order by name, but keep first major at top
		
		if (Gate::denies('member-matches', $member) && Gate::denies('permission', 'members')) {
			$request->session()->flash('msg', 'Error: Permission Denied');
			return $this->getMember($request, $member->id);
		}
		
		return view('pages.member-edit',compact("member","majors"));
	}
	
	public function findMember($memberID) {
		$member = Member::find($memberID);
		
		if (is_null($member)) {
			$member = Member::where('username', $memberID)->first();
		}
		
		return $member;
	}
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	public function postMember(EditMemberRequest $request, $memberID) {
		$member = $this->findMember($memberID);
		
		$memberName = $request->input('memberName');
		$username = $request->input('username');
		$password = $request->input('password');
		$email = $request->input('email');
		$phone = $request->input('phone');
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
		if (is_null($member)) {
			return redirect()->action('MemberController@getIndex')->with('msg', 'Error: Member Not Found');
		}
		if ($email != $member->email && Member::where('email',$email)->first()) {
			return redirect()->action('MemberController@getMemberEdit', [$member->username])->with('msg', 'Error: An account already exists with that email');
		}
		
		//// Edit Member ////
		$member->name = $memberName;
		$member->username = $this->generateUsername($member,$username);
		
		// Password
		if(strlen($password) > 0) {
			$member->password = Hash::make($password);
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
		$member->phone = $phone;
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
		if ($memberID != $member->username) {
			if ($member->id == Auth::user()->id) { // Changed own username, update session
				$request->session()->put('member_username', $member->username);
			}
			return redirect()->action('MemberController@getMember', [$member->username])->with('msg', 'Profile Saved! Your username is now '.$member->username);
		}
		
		return redirect()->action('MemberController@getMember', [$member->username])->with('msg', 'Profile Saved!');
	}
	
	/////////////////////////////// Usernames ///////////////////////////////
	
	public function generateUsername($member, $username="") {
		if (strlen($member->username) > 2 && $member->username == $username) { // Username Unchanged
			return $username;
		}
		if ($username != "" && $this->usernameAvailable($username)) { // Username Available
			return $username;
		} 
		
		if ($username == "") { // No Username Provided
			$username = strtolower(str_replace(" ", "", $member->name));
		}
		$usernameOrig = $username;
		
		$i = 1;
		while ($this->usernameAvailable($username) == false) { // Concatenate unique username.id
			$username = $usernameOrig.$i++;
		}
		
		return $username;
	}
	
	public function usernameAvailable($username) {
		$member = Member::where('username',$username)->first();
		
		// TODO: Verify URL not reserved
		
		if ($member) {
			return false;
		}
		
		return true;
	}
    
}