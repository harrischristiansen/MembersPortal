<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-05-02
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationRecord extends Model {
   	protected $table = 'location-member';
   	
	public function member() {
		return $this->hasOne('App\Models\Member', 'id', 'member_id');
	}
   	
	public function location() {
		return $this->hasOne('App\Models\Location', 'id', 'location_id');
	}
}
