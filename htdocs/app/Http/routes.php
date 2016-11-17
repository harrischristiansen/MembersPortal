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
    Route::get('/event/{id}', 'EventController@getEvent');
    Route::controller('/locations', 'LocationController');
    Route::get('/location/{id}', 'LocationController@getLocation');
    Route::controller('/members', 'MemberController');
    Route::controller('/projects', 'ProjectController');
    Route::controller('/reports', 'ReportsController');
    Route::get('/anvil-wifi', 'PortalController@getAnvilWifi');
    Route::get('/hackathons', 'PortalController@getHackathons');
    Route::get('/{username}', 'MemberController@getMember');
    Route::post('/{username}', 'MemberController@postMember');
    Route::get('/', 'PortalController@getIndex');
});