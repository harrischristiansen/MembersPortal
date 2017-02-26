<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /////////////////////////////// Home ///////////////////////////////

    public function getIndex(Request $request)
    {
        return view('pages.home.home');
    }

    /////////////////////////////// Misc Pages ///////////////////////////////

    public function getAnvilWifi()
    {
        return view('pages.home.anvilWifi');
    }
}
