<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    2016-04-25
    Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use App\Http\Requests\LoggedInRequest;
use App\Models\Event;
use App\Models\Location;
use App\Models\Member;

class AutocompleteController extends BaseController
{
    public function getMembers(LoggedInRequest $request, $eventID = 0)
    {
        $requestTerm = $request->input('term');
        $searchFor = '%'.$requestTerm.'%';

        $members = Member::where('name', 'LIKE', $searchFor)->orWhere('email', 'LIKE', $searchFor)->orWhere('email_public', 'LIKE', $searchFor)->orWhere('email_edu', 'LIKE', $searchFor)->orWhere('phone', 'LIKE', $searchFor)->orWhere('description', 'LIKE', $searchFor)->get();

        if ($eventID != 0) {
            $event = Event::findOrFail($eventID);
        }

        $results = [];
        $numResults = count($members);
        for ($i = 0; $i < $numResults; $i++) {
            $results[$i]['value'] = $members[$i]->name;
            $results[$i]['name'] = $members[$i]->name;
            $results[$i]['email'] = $members[$i]->email;
            $results[$i]['phone'] = $members[$i]->phone;
            $results[$i]['attended'] = count($members[$i]->events);
            $results[$i]['graduation_year'] = $members[$i]->graduation_year;
            if ($numResults <= 10 && $eventID != 0 && $event->requiresApplication) {
                $results[$i]['registered'] = count($event->applications()->where('member_id', $members[$i]->id)->get());
            }
        }

        return $results;
    }

    public function getLocations(LoggedInRequest $request)
    {
        $requestTerm = $request->input('term');

        $searchFor = '%'.$requestTerm.'%';
        $results = Location::where('name', 'LIKE', $searchFor)->get();

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]['value'] = $results[$i]['name'];
        }

        return $results;
    }

    public function getCities(LoggedInRequest $request)
    {
        $requestTerm = $request->input('term');

        $searchFor = '%'.$requestTerm.'%';
        $results = Location::where('city', 'LIKE', $searchFor)->get();

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]['value'] = $results[$i]['city'];
        }

        return $results;
    }
}
