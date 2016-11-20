<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-05-02
	Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {
	
	protected $fillable = ['name','city'];
   	
	public function members() {
		return $this->hasMany('App\Models\LocationRecord');
	}
	
}
