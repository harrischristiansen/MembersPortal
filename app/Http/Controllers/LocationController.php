<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    2016-04-25
    Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\LoggedInRequest;
use App\Models\Location;
use App\Models\LocationRecord;
use App\Models\Member;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class LocationController extends BaseController
{
    /////////////////////////////// Viewing Locations ///////////////////////////////

    public function getIndex(Request $request)
    {
        $locations = Location::all();

        return view('pages.locations', compact('locations'));
    }

    public function getMap()
    {
        $locations = Location::all();

        return view('pages.map', compact('locations'));
    }

    public function getMapData()
    {
        $locations = Location::all();
        foreach ($locations as $key=>$location) {
            $locations[$key]['members'] = $location->members()->count();
        }

        return $locations;
    }

    public function getLocation(Request $request, $locationID)
    {
        $location = Location::find($locationID);

        if (is_null($location)) {
            $request->session()->flash('msg', 'Error: Location Not Found.');

            return $this->getIndex($request);
        }

        $members = $location->members;

        return view('pages.location', compact('location', 'members'));
    }

    /////////////////////////////// Editing Locations ///////////////////////////////

    public function postLocation(AdminRequest $request, $locationID)
    {
        $location = Location::find($locationID);

        if (is_null($location)) {
            $request->session()->flash('msg', 'Error: Location Not Found.');

            return $this->getIndex($request);
        }

        $location->name = $request->input('locationName');
        $location->city = $request->input('city');
        $location->save();

        return $this->getLocation($locationID);
    }

    public function postCreate(LoggedInRequest $request, $memberID)
    {
        $locationName = $request->input('locationName');
        $city = $request->input('city');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $member = Member::find($memberID);

        if (strlen($locationName) < 3 || strlen($city) < 3 || strlen($date_start) < 3) {
            return redirect()->action('MemberController@getMember', $member->username)->with('msg', 'Error: Location name, city, and start date required');
        }
        if (is_null($member)) {
            return redirect()->action('MemberController@getIndex')->with('msg', 'Error: Member Not Found');
        }
        if (Gate::denies('member-matches', $member) && Gate::denies('permission', 'members')) {
            return redirect()->action('MemberController@getIndex')->with('msg', 'Error: Member Not Found');
        }

        $location = Location::firstOrCreate(['name'=>$locationName, 'city'=>$city]);

        if ($location->loc_lat == 0) {
            $this->addLocationLatLng($location);
        }

        $locationRecord = new LocationRecord();
        $locationRecord->member_id = $memberID;
        $locationRecord->location_id = $location->id;
        $locationRecord->date_start = new Carbon($date_start);
        if ($date_end != '') {
            $locationRecord->date_end = new Carbon($date_end);
        }
        $locationRecord->save();

        return redirect()->action('MemberController@getMember', $member->username)->with('msg', 'Location Record Added!');
    }

    public function addLocationLatLng($location)
    {
        // Get Correct Latitude / Longitude of Location from Google Places API
        $requestQuery = htmlentities(urlencode($location->name.' '.$location->city));
        $requestResult = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$requestQuery.'&key='.env('KEY_GOOGLESERVER')), true);

        if (count($requestResult['results']) > 0) {
            $location->loc_lat = $requestResult['results'][0]['geometry']['location']['lat'];
            $location->loc_lng = $requestResult['results'][0]['geometry']['location']['lng'];
        }

        $location->save();
    }

    public function getDelete(LoggedInRequest $request, $recordID)
    {
        $locationRecord = LocationRecord::find($recordID);
        $authenticated_id = $request->session()->get('member_id');

        if (is_null($locationRecord)) {
            return redirect()->action('MemberController@getIndex')->with('msg', 'Error: Location Record Not Found');
        }

        $member = $locationRecord->member;

        if (Gate::denies('member-matches', $member) && Gate::denies('permission', 'members')) {
            return redirect()->action('MemberController@getIndex')->with('msg', 'Error: Location Record Not Found');
        }

        $locationRecord->delete();

        return redirect()->action('MemberController@getMember', $member->username)->with('msg', 'Location Record Deleted!');
    }
}
