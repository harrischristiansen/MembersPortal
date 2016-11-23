<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller {
	
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
}
