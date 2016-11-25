<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CredentialRequest;
use App\Models\Credential;

class CredentialController extends BaseController {
	
	public function getIndex(Request $request) {
		if (Gate::denies('permission', 'credentials')) {
			return redirect()->guest('login')->with('msg', 'Permission Denied');
		}
		
		$credentials = Credential::all();
		return view('pages.credentials', compact("credentials"));
	}
	
	public function postIndex(CredentialRequest $request) {
		$site = $request->input("site");
		$username = $request->input("username");
		$password = $request->input("password");
		$description = $request->input("description");
		
		if (strlen($site) < 3 || strlen($username) < 3 || strlen($password) < 3) {
			$request->session()->flash('msg', 'Error: Please provide site, username, and password');
			return $this->getCredentials($request);
		}
		
		$credential = new Credential;
		$credential->site = $site;
		$credential->username = $username;
		$credential->password = encrypt($password);
		$credential->description = $description;
		$credential->member_id = Auth::user()->id;
		$credential->save();
		
		$request->session()->flash('msg', 'Success: Added credentials for '.$credential->site);
		return $this->getIndex($request);
	}
	
	public function getDelete(CredentialRequest $request, $credentialID) {
		$credential = Credential::findOrFail($credentialID);
		$credential->delete();
		
		return redirect()->action('CredentialController@getIndex')->with('msg', 'Success: Delete credentials for '.$credential->site.'.');
	}
    
}