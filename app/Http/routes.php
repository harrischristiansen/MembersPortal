<?php

/*
    @ Harris Christiansen (Harris@HarrisChristiansen.com)
    2016-04-25
    Project: Members Tracking Portal
*/

Route::group(['middleware' => ['web']], function () {
    Route::controller('/auth', 'AuthController');
    Route::get('/join', 'AuthController@getJoin');
    Route::get('/login', 'AuthController@getLogin');
    Route::controller('/autocomplete', 'AutocompleteController');
    Route::controller('/credentials', 'CredentialController');
    Route::controller('/permissions', 'PermissionController');
    Route::controller('/hackathons', 'HackathonController');
    Route::controller('/events', 'EventController');
    Route::get('/event/{id}', 'EventController@getEvent');
    Route::controller('/locations', 'LocationController');
    Route::get('/location/{id}', 'LocationController@getLocation');
    Route::controller('/members', 'MemberController');
    Route::controller('/projects', 'ProjectController');
    Route::get('/project/{id}', 'ProjectController@getProject');
    Route::controller('/reports', 'ReportsController');
    Route::get('/anvil-wifi', 'HomeController@getAnvilWifi');
    Route::get('/tshirt', 'HomeController@getTshirt');
    Route::get('/', 'HomeController@getIndex')->name('home');
    Route::get('/{username}', 'MemberController@getMember');
    Route::post('/{username}', 'MemberController@postMember');
});
