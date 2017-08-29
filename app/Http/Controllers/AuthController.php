<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    File Created: Nov 2016
    Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use App;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Member;
use App\Models\Major;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;

class AuthController extends BaseController
{
    /////////////////////////////// Account Login ///////////////////////////////

    public function getLogin()
    {
        return view('pages.auth.login');
    }

    public function postLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember');

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $member = Auth::user();
            $member->authenticated_at = Carbon::now();
            $member->timestamps = false; // Don't update timestamps
            $member->save();

            $request->session()->flash('msg', 'Welcome '.$member->name.'!');

            return redirect()->intended('');
        }

        // MD5 Backward Compatability
        $passwordMD5 = md5($password);
        $member = Member::where('email', $email)->where('password', $passwordMD5)->first();
        if ($member) {
            $member->authenticated_at = Carbon::now();
            $member->password = Hash::make($password);
            $member->timestamps = false; // Don't update timestamps
            $member->save();

            Auth::login($member);
            $request->session()->flash('msg', 'Welcome '.$member->name.'!');

            return redirect()->intended('');
        }

        $request->session()->flash('msg-red', 'Invalid username or password.');

        return $this->getLogin();
    }

    /////////////////////////////// Account Logout ///////////////////////////////

    public function getLogout(Request $request)
    {
        Auth::logout();

        $request->session()->flash('msg', 'You are now logged out');

        return $this->getIndex($request);
    }

    /////////////////////////////// Account Register ///////////////////////////////

    public function getJoin()
    {
        $member = new Member();
        $majors = Major::orderByRaw('(id = 1) DESC, name')->get();
        return view('pages.auth.register', compact('member', 'majors'));
    }

    public function postJoin(RegisterRequest $request)
    {
        $memberName = $request->input('memberName');
        $privateProfile = $request->input('privateProfile');
        $email = $request->input('email');
        $unsubscribed = $request->input('unsubscribed');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirmation');
        $gradYear = $request->input('gradYear');
        $phone = $request->input('phone');
        $email_public = $request->input('email_public');
        $description = $request->input('description');
        $major = $request->input('major');
        $gender = $request->input('gender');
        $facebook = $request->input('facebook');
        $github = $request->input('github');
        $linkedin = $request->input('linkedin');
        $devpost = $request->input('devpost');
        $website = $request->input('website');
        // Resume, Profile Picture

        // Validate Input
        if ($memberName == '' || $email == '' || $password == '' || $gradYear == '') {
            $request->session()->flash('msg', 'Please enter all fields.');

            return $this->getJoin();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $request->session()->flash('msg', 'Invalid Email Address.');

            return $this->getJoin();
        }

        if ($password != $password_confirm) {
            $request->session()->flash('msg', 'Passwords did not match.');

            return $this->getJoin();
        }

        if (Member::where('email', $email)->first()) {
            $request->session()->flash('msg', 'An account already exists with that email. Please use your '.env('DB_ORG_NAME').' account password if you have one.');

            return $this->getJoin();
        }

        // Create Account + Authenticate
        $member = $this->createAccount($memberName, $email, $password, $gradYear, $email_public);
        Auth::login($member);

        // Add Additional information to member
        $member->privateProfile = $privateProfile == 'true';
        $member->unsubscribed = $unsubscribed == 'true';
        $member->phone = $phone;
        $member->graduation_year = $gradYear;
        $member->gender = $gender;
        if ($major > 0) {
            $member->major_id = $major;
        }
        $member->description = $description;
        $member->facebook = $facebook;
        $member->github = $github;
        $member->linkedin = $linkedin;
        $member->devpost = $devpost;
        $member->website = $website;

        // Picture
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            if ($picture->isValid() && (strtolower($picture->getClientOriginalExtension()) == 'jpg' ||
                    strtolower($picture->getClientOriginalExtension()) == 'png') && (strtolower($picture->getClientMimeType()) == 'image/jpeg' ||
                    strtolower($picture->getClientMimeType()) == 'image/jpg' || strtolower($picture->getClientMimeType()) == 'image/png')) {
                $fileName = $picture->getClientOriginalName();
                $uploadPath = 'uploads/member_pictures/'; // base_path().'/public/uploads/member_pictures/
                $fileName_disk = $member->id.'_'.substr(md5($fileName), -6).'.'.$picture->getClientOriginalExtension();
                $picture->move($uploadPath, $fileName_disk);
                $member->picture = $fileName;
            }
        }

        // Resume
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            if ($resume->isValid() && strtolower($resume->getClientOriginalExtension()) == 'pdf' && strtolower($resume->getClientMimeType()) == 'application/pdf') {
                $fileName = $resume->getClientOriginalName();
                $uploadPath = 'uploads/resumes/'; // base_path().'/public/uploads/resumes/
                $fileName_disk = $member->id.'_'.substr(md5($fileName), -6).'.'.$resume->getClientOriginalExtension();
                $resume->move($uploadPath, $fileName_disk);
                $member->resume = $fileName;
            }
        }

        $member->save();

        $request->session()->flash('msg', 'Welcome '.$member->name.'!');

        return $this->getIndex($request);
    }

    public function createAccount($name, $email, $password, $gradYear, $email_public)
    {
        // Create Member
        $member = new Member();
        $member->name = $name;
        $member->username = app('app\Http\Controllers\MemberController')->generateUsername($member);
        $member->email = $email;
        $member->email_public = $email_public;

        if (strlen($password) > 2) {
            $member->password = Hash::make($password);
        }

        if (strpos($email, '.edu') !== false) {
            $member->email_edu = $email;
        }

        if (strpos($email_public, '.edu') !== false) {
            $member->email_edu = $email_public;
        }

        $member->graduation_year = $gradYear;
        $member->save();

        return $member;
    }

    /////////////////////////////// Password Reset Request ///////////////////////////////

    public function getForgot(Request $request)
    {
        return view('pages.auth.reset');
    }

    public function postForgot(Request $request)
    {
        $email = $request->input('email');

        $member = Member::where('email', $email)->first();

        if ($member == null) {
            $request->session()->flash('msg', 'No account was found with that email!');

            return $this->getForgot($request);
        }

        $this->emailResetRequest($member);

        $request->session()->flash('msg', 'A link to reset your password has been sent to your email!');

        return $this->getForgot($request);
    }

    public function emailResetRequest($member)
    {
        if (App::environment('local', 'staging') && strpos($member->email, 'harrischristiansen.com') === false) {
            return false;
        }

        Mail::send('emails.resetRequest', ['member'=>$member], function ($message) use ($member) {
            $message->from('purduehackers@gmail.com', 'Purdue Hackers');
            $message->to($member->email);
            $message->subject('Reset your Purdue Hackers account password');
        });
    }

    /////////////////////////////// Perform Password Reset Form ///////////////////////////////

    public function getReset(Request $request, $memberID, $reset_token)
    {
        $member = Member::find($memberID);
        if ($reset_token != $member->reset_token()) {
            $request->session()->flash('msg', 'Error: Invalid Password Reset Link');

            return $this->getIndex($request);
        }

        Auth::login($member);

        $memberRequest = app('app\Http\Controllers\MemberController')->getMemberEdit($request, $memberID);
        $setPassword = true;

        return $memberRequest->with(compact('setPassword'));
    }

    /////////////////////////////// Account Setup Emails ///////////////////////////////

    public function getSetupAccountEmails(AdminRequest $request)
    { // Batch email all accounts that have not been setup, prompting them to setup.
        $members = Member::where('graduation_year', 0)->get();

        $nowDate = Carbon::now();

        $emailsSent = 0;
        foreach ($members as $member) {
            if ($member->setupEmailSent->diffInDays($nowDate) > 30) {
                $this->emailAccountCreated($member, $member->events()->first());
                $emailsSent++;
            }
        }

        $request->session()->flash('msg', 'Success, '.$emailsSent.' setup account emails have been sent!');

        return $this->getIndex($request);
    }
}
