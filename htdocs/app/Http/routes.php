<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

Route::group(['middleware' => ['web']], function () {
    Route::controller('/', 'PortalController');
});