<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SuperAdminRequest;
use App\Models\Credential;

class CredentialController extends BaseController {
	
	public function getIndex(Request $request) {
		if (Auth::user()->setupAdmin) {
			$credentials = Credential::all();
		
			return view('pages.credentials', compact("credentials"));
		}
		
		return "Permission Denied.";
	}
	
	public function postIndex(SuperAdminRequest $request) {
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
		$credential->member_id = $this->getAuthenticatedID($request);
		$credential->save();
		
		return $this->getIndex($request);
	}
	
	public function getDelete(SuperAdminRequest $request, $credentialID) {
		$credential = Credential::findOrFail($credentialID);
		$credential->delete();
		
		return redirect()->action('CredentialController@getIndex')->with('msg', 'Success: Delete credentials for '.$credential->site.'.');
	}
    
}