<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {
   	
	public function events() {
		return $this->belongsToMany('App\Models\Event');
	}
	
}
