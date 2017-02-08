<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    2016-04-25
    Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use App;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

use Twilio\Rest\Client;

class BaseController extends Controller
{
    /////////////////////////////// Home ///////////////////////////////

    public function getIndex(Request $request)
    {
        return view('pages.home');
    }

    /////////////////////////////// Email ///////////////////////////////

    public function sendEmail($member, $subject, $msg)
    {
        if (App::environment('local', 'staging') && strpos($member->email, 'harrischristiansen.com') === false) {
            return false;
        }

        Mail::send('emails.default', ['member'=>$member, 'msg'=>$msg], function ($message) use ($member, $subject) {
            $message->from('purduehackers@gmail.com', 'Purdue Hackers');
            $message->to($member->email);
            $message->subject($subject);
        });
    }

    public function emailAccountCreated($member, $event)
    {
        if (App::environment('local', 'staging') && strpos($member->email, 'harrischristiansen.com') === false) {
            return false;
        }

        Mail::send('emails.accountCreated', ['member'=>$member, 'event'=>$event], function ($message) use ($member) {
            $message->from('purduehackers@gmail.com', 'Purdue Hackers');
            $message->to($member->email);
            $message->subject('Welcome '.$member->name.' to Purdue Hackers!');
        });
        $member->setupEmailSent = Carbon::now();
        $member->save();
    }

    /////////////////////////////// Twilio ///////////////////////////////

    public static $twilioClient;

    public function TwilioClient()
    {
        if (null === static::$twilioClient) {
            static::$twilioClient = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        }

        return static::$twilioClient;
    }

    public function sendSMS($member, $msg)
    {
        $msg = str_replace(['<br />', '<br>'], ['', ''], $msg);

        if (App::environment('local', 'staging') && !$member->isAdmin()) {
            return false;
        }

        if (strlen($member->phone) > 7) {
            $phoneNum = preg_replace('/[^0-9]/', '', $member->phone);
            if (strlen($phoneNum) == 10) {
                $this->TwilioClient()->messages->create($phoneNum, ['from'=>'+17652312066', 'body'=>$msg]);
            }
        }
    }

    /////////////////////////////// Generate Random Int ///////////////////////////////

    public static function generateRandomInt()
    {
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
