<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-08-24
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {
	public function member() {
		return $this->belongsTo('App\Models\Member');
	}
	
	public function event() {
		return $this->hasOne('App\Models\Event');
	}
}
