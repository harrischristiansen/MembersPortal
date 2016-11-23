<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

	public function members() {
		return $this->belongsToMany('App\Models\Member')->withPivot('recorded_by');
	}
}
