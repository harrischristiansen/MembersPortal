<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	For: Purdue Hackers - Membership Portal
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;

use App\Models\Member;
use App\Models\Event;

class PHController extends Controller {
	
	/////////////////////////////// Home ///////////////////////////////
    
    public function getIndex() {
		return view('pages.home');
	}
	
	/////////////////////////////// Authentication ///////////////////////////////
	
	public function getLogin() {
		return view('pages.login');
	}

	public function postLogin(Request $request) {
		$password = $request->input('password');

		if($password == env('ADMIN_PASS')) {
			$request->session()->put('authenticated_admin', 'true');
		}

		return $this->getIndex();
	}

	public function getLogout(Request $request) {
		$request->session()->put('authenticated_member', 'false');
		$request->session()->put('authenticated_admin', 'false');

		return $this->getIndex();
	}
	
	/////////////////////////////// Resource Pages ///////////////////////////////
    
    public function getAnvilWifi() {
		return view('pages.anvilWifi');
	}
	
	/////////////////////////////// Viewing Members ///////////////////////////////
	
	/////////////////////////////// Editing Members ///////////////////////////////
	
	/////////////////////////////// Viewing Events ///////////////////////////////
	
	/////////////////////////////// Managing Events ///////////////////////////////

	/////////////////////////////// Helper Functions ///////////////////////////////
	
	public static function generateRandomInt() {
        srand();
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
