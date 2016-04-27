<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function members() {
		return $this->belongsToMany('App\Models\Member');
	}
}
