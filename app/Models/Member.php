<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Models;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable {
	
	protected $hidden = ['password', 'remember_token',];
	protected $dates = ['created_at','updated_at','authenticated_at','setupEmailSent',];
	
	public function permissions() {
		return $this->hasMany('App\Models\Permission')->withPivot('recorded_by');
	}
	
	public function profileURL() {
		return action('MemberController@getMember', $this->username);
	}

	public function major() {
		return $this->hasOne('App\Models\Major','id','major_id');
	}
	
	public function locations() {
		return $this->hasMany('App\Models\LocationRecord');
	}
	
	public function projects() {
		return $this->belongsToMany('App\Models\Project');
	}
	
	public function events() {
		return $this->belongsToMany('App\Models\Event')->withPivot('recorded_by');
	}
	
	public function publicEventCount() {
		return Cache::get('member_public_events_'.$this->id,$this->buildPublicEventCountCache());
	}
	
	public function applications() {
		return $this->hasMany('App\Models\Application');
	}
	
	public function apply_url($eventID) {
		return action('EventController@getApply', [$eventID, $this->id, $this->reset_token()]);
	}
	
	public function reset_token() {
		return md5($this->id.$this->password.env('ADMIN_PASS'));
	}
	
	public function reset_url() {
		return action('AuthController@getReset', [$this->id, $this->reset_token()]);
	}
	
	public function isAdmin() {
		return ($this->admin == 1);
	}
	
	public function picturePath() {
		$fileExt = pathinfo($this->picture, PATHINFO_EXTENSION);
		return '/uploads/member_pictures/'.$this->id."_".substr(md5($this->picture), -6).'.'.$fileExt;
	}
	
	public function resumePath() {
		$fileExt = pathinfo($this->resume, PATHINFO_EXTENSION);
		return '/uploads/resumes/'.$this->id."_".substr(md5($this->resume), -6).'.'.$fileExt;
	}
	public function buildPublicEventCountCache() {
		return Cache::remember('member_public_events_'.$this->id, 65, function () {
		    return $this->events()->where('privateEvent',false)->count();
		});
	}
	
}
