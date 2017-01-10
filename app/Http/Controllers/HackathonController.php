<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Hackathon;
use Illuminate\Http\Request;

class HackathonController extends Controller
{
    /////////////////////////////// Hackathon List ///////////////////////////////

    public function getIndex(Request $request)
    {
        $hackathons = Hackathon::all();

        return view('pages.hackathons', compact('hackathons'));
    }

    public function postIndex(EventRequest $request)
    {
        $name = $request->input('name');
        $website = $request->input('website');
        $date = $request->input('date');
        $location = $request->input('location');
        $applyBy = $request->input('apply');

        if (strlen($name) < 3 || strlen($website) < 3 || strlen($date) < 3) {
            $request->session()->flash('msg', 'Error: Hackathon name, website, and event date are required');

            return $this->getIndex($request);
        }

        $hackathon = new Hackathon();
        $hackathon->name = $name;
        $hackathon->website = $website;
        $hackathon->date = $date;
        $hackathon->location = $location;
        $hackathon->apply = $applyBy;
        $hackathon->save();

        $request->session()->flash('msg', 'Success: Added hackathon '.$hackathon->name);

        return $this->getIndex($request);
    }

    public function getDelete(EventRequest $request, $hackathonID)
    {
        $hackathon = Hackathon::findOrFail($hackathonID);
        $hackathon->delete();

        return redirect()->action('HackathonController@getIndex')->with('msg', 'Success: Removed hackathon '.$hackathon->name);
    }
}
