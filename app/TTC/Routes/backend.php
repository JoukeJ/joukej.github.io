<?php

/**
 * Here we place all routes for the backend of the TTC application
 */

Route::group(['middleware' => 'auth'], function () {

    Route::resource('/', 'BackendController@index');
    Route::get('/', ['as' => 'home', 'uses' => 'BackendController@index']);

    Route::resource('surveys', 'SurveyController');
    Route::get('/my-surveys', ['as' => 'surveys.my_index', 'uses' => 'SurveyController@myIndex']);
    Route::get('/surveys/exportsmsdata/{surveyId}', ['as' => 'surveys.exportsmsdata', 'uses' => 'SurveyController@exportSMSData']);
    Route::post('/surveys/bootgrid', ['as' => 'surveys.bootgrid', 'uses' => 'SurveyController@bootgrid']);
    Route::post('/surveys/my-bootgrid', ['as' => 'surveys.my.bootgrid', 'uses' => 'SurveyController@myBootgrid']);
    Route::post('/surveys/close/{surveyId}', ['as' => 'surveys.close', 'uses' => 'SurveyController@close']);
    Route::post('/surveys/open/{surveyId}', ['as' => 'surveys.open', 'uses' => 'SurveyController@open']);
    Route::post('/surveys/cancel/{surveyId}', ['as' => 'surveys.cancel', 'uses' => 'SurveyController@cancel']);

    // Main survey show
    Route::get('/surveys/{surveyId}', ['as' => 'survey.show', 'user' => 'SurveyController@show'])
        ->where('surveyId', '[\d]+');

    Route::resource('/surveys/{surveys}/entities', 'Survey\EntityController', [
        'names' => [
            'create' => 'survey.entities.create',
            'store' => 'survey.entities.store',
            'edit' => 'survey.entities.edit',
            'update' => 'survey.entities.update',
            'destroy' => 'survey.entities.destroy',
        ]
    ]);

    Route::resource('/surveys/{surveys}/matchgroups', 'Survey\MatchgroupController', [
        'names' => [
            'create' => 'survey.matchgroups.create',
            'store' => 'survey.matchgroups.store',
            'edit' => 'survey.matchgroups.edit',
            'update' => 'survey.matchgroups.update',
            'destroy' => 'survey.matchgroups.destroy',
        ]
    ]);

    Route::resource('/surveys/{surveys}/statistics', 'Survey\StatisticController');

    Route::get('/surveys/{surveys}/export', [
        'as' => 'surveys.statistic.export',
        'uses' => 'Survey\StatisticController@export'
    ]);

    Route::get('/csv', ['as' =>  'csv.index', 'uses' => 'CsvController@index']);
    Route::post('/csv/import', ['as' =>  'csv.import', 'uses' => 'CsvController@import']);
    Route::get('/csv/export', ['as' =>  'csv.export', 'uses' => 'CsvController@export']);
});
