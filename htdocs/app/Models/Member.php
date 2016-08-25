<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {

	public function major() {
		return $this->hasOne('App\Models\Major','id','major_id');
	}
	
	public function locations() {
		return $this->hasMany('App\Models\LocationRecord');
	}
	
	public function events() {
		return $this->belongsToMany('App\Models\Event');
	}
	
	public function applications() {
		return $this->hasMany('App\Models\Application');
	}
	
	public function reset_token() {
		return md5($this->id.$this->password.env('ADMIN_PASS'));
	}
	
}
