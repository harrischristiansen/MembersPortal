<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    Nov 10, 2016
    Project: Members Tracking Portal
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
