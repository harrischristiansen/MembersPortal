<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-08-24
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model {
	public function members() {
		return $this->belongsToMany('App\Models\Member');
	}
}
