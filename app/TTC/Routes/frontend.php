<?php
/**
 * Here we place all routes for the frontend of the TTC application
 */

Route::get('/profile/{identifier}/match', [
    'as' => 'profile.match',
    'uses' => 'ProfileController@match'
]);

Route::resource('profile', 'ProfileController', [
    'only' => [
        'show',
        'edit',
        'update'
    ]
]);

Route::get('/survey/{profileId}/{surveyId}/complete', [
    'as' => 'survey.complete',
    'uses' => 'SurveyController@complete'
]);

Route::get('/survey/{profileId}/{surveyId}/{entityId}', [
    'as' => 'survey',
    'uses' => 'SurveyController@get'
]);

Route::post('/survey/{profileId}/{surveyId}/{entityId}', [
    'as' => 'survey.post',
    'uses' => 'SurveyController@post'
]);

Route::get('/image/{entityId}', [
    'as' => 'image',
    'uses' => 'InfoController@image'
]);

// Short url to profile
Route::get('/{identifier}', function($identifier){
    return Redirect::to(URL::route('profile.show', $identifier));
})->where('identifier', '[A-Za-z0-9]{8}');
