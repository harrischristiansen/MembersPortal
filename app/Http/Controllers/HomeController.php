<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /////////////////////////////// Home ///////////////////////////////

    public function getIndex(Request $request)
    {
        return view('pages.home');
    }

    /////////////////////////////// Misc Pages ///////////////////////////////

    public function getAnvilWifi()
    {
        return view('pages.anvilWifi');
    }
}
