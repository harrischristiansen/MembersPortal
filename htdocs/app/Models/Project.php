<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-10-09
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model {
	use SoftDeletes;

	public function members() {
		return $this->belongsToMany('App\Models\Member');
	}
	
	public function event() {
		return $this->belongsTo('App\Models\Event');
	}
}
