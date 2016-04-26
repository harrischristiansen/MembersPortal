<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	For: Purdue Hackers - Membership Portal
*/

Route::group(['middleware' => ['web']], function () {
    Route::controller('/', 'PHController');
});