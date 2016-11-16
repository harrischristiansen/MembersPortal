<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

Route::group(['middleware' => ['web']], function () {
    Route::controller('/auth', 'AuthController');
    Route::controller('/autocomplete', 'AutocompleteController');
    Route::controller('/credentials', 'CredentialController');
    Route::controller('/events', 'EventController');
    Route::controller('/locations', 'LocationController');
    Route::controller('/members', 'MemberController');
    Route::controller('/projects', 'ProjectController');
    Route::controller('/reports', 'ReportsController');
    Route::get('/anvil-wifi', 'PortalController@getAnvilWifi');
    Route::get('/hackathons', 'MemberController@getHackathons');
    Route::get('/{username}', 'MemberController@getMember');
    Route::get('/', 'PortalController@getIndex');
});