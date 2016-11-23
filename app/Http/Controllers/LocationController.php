<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\AdminRequest;

use App\Models\Location;
use App\Models\LocationRecord;
use App\Models\Member;

class LocationController extends BaseController {
	
	/////////////////////////////// Viewing Locations ///////////////////////////////
	
	public function getIndex(Request $request) {
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
	
	public function getLocation(Request $request, $locationID) {
		$location = Location::find($locationID);
		
		if(is_null($location)) {
			$request->session()->flash('msg', 'Error: Location Not Found.');
			return $this->getIndex($request);
		}
		
		$members = $location->members;
		
		return view('pages.location',compact("location","members"));
	}
	
	/////////////////////////////// Editing Locations ///////////////////////////////
	
	public function postLocation(AdminRequest $request, $locationID) {
		$location = Location::find($locationID);
		
		if(is_null($location)) {
			$request->session()->flash('msg', 'Error: Location Not Found.');
			return $this->getIndex($request);
		}
		
		$location->name = $request->input('locationName');
		$location->city = $request->input('city');
		$location->save();
		
		return $this->getLocation($locationID);
	}
	
	public function postCreate(LoggedInRequest $request, $memberID) {
		$locationName = $request->input("locationName");
		$city = $request->input("city");
		$date_start = $request->input("date_start");
		$date_end = $request->input("date_end");
		
		$member = Member::find($memberID);
		$authenticated_id = $request->session()->get('member_id');
		
		if (is_null($member)) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return app('App\Http\Controllers\MemberController')->getIndex();
		}
		if (Gate::denies('admin') && $memberID!=$authenticated_id) {
			$request->session()->flash('msg', 'Error: Member Not Found.');
			return app('App\Http\Controllers\MemberController')->getIndex();
		}
		
		$location = Location::firstOrCreate(['name'=>$locationName, 'city'=>$city]);
		
		if ($location->loc_lat==0) {
			$this->addLocationLatLng($location);
		}
		
		$locationRecord = new LocationRecord;
		$locationRecord->member_id = $memberID;
		$locationRecord->location_id = $location->id;
		$locationRecord->date_start = new Carbon($date_start);
		$locationRecord->date_end = new Carbon($date_end);
		$locationRecord->save();
		
		$request->session()->flash('msg', 'Location Record Added!');
		return app('App\Http\Controllers\MemberController')->getMember($request, $memberID);
	}
	
	public function addLocationLatLng($location) {
		// Get Correct Latitude / Longitude of Location from Google Places API
		$requestQuery = htmlentities(urlencode($location->name." ".$location->city));
		$requestResult = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$requestQuery.'&key='.env('KEY_GOOGLESERVER')), true);
		
		if (count($requestResult["results"]) > 0) {
			$location->loc_lat = $requestResult["results"][0]["geometry"]["location"]["lat"];
			$location->loc_lng = $requestResult["results"][0]["geometry"]["location"]["lng"];
		}
		
		$location->save();
	}
	
	public function getDelete(LoggedInRequest $request, $recordID) {
		$locationRecord = LocationRecord::find($recordID);
		$authenticated_id = $request->session()->get('member_id');
		
		if(is_null($locationRecord)) {
			$request->session()->flash('msg', 'Error: Location Record not Found.');
			return app('App\Http\Controllers\MemberController')->getIndex();
		}
		if($request->session()->get('authenticated_member') != "true" && $locationRecord->member->id != $authenticated_id) {
			$request->session()->flash('msg', 'Error: Location Record not Found.');
			return app('App\Http\Controllers\MemberController')->getIndex();
		}
		
		$return_memberID = $locationRecord->member->id;
		$locationRecord->delete();
		
		return redirect()->action('MemberController@getMember', [$return_memberID])->with('msg', 'Location Record Deleted!');
	}
    
}