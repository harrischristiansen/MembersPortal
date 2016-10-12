<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model {
	use SoftDeletes;
	protected $dates = ['event_time','deleted_at'];

	public function members() {
		return $this->belongsToMany('App\Models\Member')->withPivot('recorded_by');
	}
	
	public function applications() {
		return $this->hasMany('App\Models\Application');
	}
	
	public function getAppliedMembers() {
		$member_ids = $this->applications()->pluck('member_id');
		$members = Member::findMany($member_ids);
		
		return $members;
	}
	
	public function date() {
		return $this->event_time ? $this->event_time->format('Y-m-d') : "-";
	}
	
	public function dateMonthDay() {
		return $this->event_time ? $this->event_time->format('M j') : "-";
	}
	
	public function dateFriendly() {
		return $this->event_time ? $this->event_time->format('D, F j, Y \a\t g:i a') : "-";
	}
	
	public function hour() {
		return $this->event_time ? $this->event_time->format('H') : "-";
	}
	
	public function minute() {
		return $this->event_time ? $this->event_time->format('i') : "-";
	}
}
