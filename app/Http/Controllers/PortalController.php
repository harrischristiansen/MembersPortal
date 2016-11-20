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

use App\Models\Member;

class PortalController extends BaseController {
	
	/////////////////////////////// Home ///////////////////////////////
    
	public function getIndex(Request $request) {
		return view('pages.home');
	}
	
	/////////////////////////////// Misc Pages ///////////////////////////////
    
    public function getHackathons() {
		return view('pages.hackathons');
	}
    
    public function getAnvilWifi() {
		return view('pages.anvilWifi');
	}	
	
	/////////////////////////////// Account Setup Emails ///////////////////////////////
	
	public function getSetupAccountEmails(AdminRequest $request) { // Batch email all accounts that have not been setup, prompting them to setup.
		$members = Member::where('graduation_year',0)->get();
		
		$nowDate = Carbon::now();
		
		foreach ($members as $member) {
			if ($member->setupEmailSent->diffInDays($nowDate) > 30) {
				$this->emailAccountCreated($member, $member->events()->first());
			}
		}
		
		$request->session()->flash('msg', 'Success, setup account emails have been sent!');
		return $this->getIndex($request);
	}
    
}