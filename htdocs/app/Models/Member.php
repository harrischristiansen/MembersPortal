<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {
   	
	public function events() {
		return $this->belongsToMany('App\Models\Event');
	}
	
	public function reset_token() {
		return md5($this->id + env('ADMIN_PASS'));
	}
	
}
