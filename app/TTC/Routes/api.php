<?php

/**
 * Here we place all routes for the api of the TTC application
 */

Route::group(['middleware' => ['auth.api', 'api.log'], 'prefix' => 'api/v1/'], function () {
    Route::get('profile/{id}', [
        'as' => 'api.v1.profile.detail',
        'uses' => 'V1\ProfileController@detail'
    ]);
    Route::post('profile', [
        'as' => 'api.v1.profile.createOrUpdate',
        'uses' => 'V1\ProfileController@createOrUpdate'
    ]);

    Route::get('country/{id}', [
        'as' => 'api.v1.country.detail',
        'uses' => 'V1\CountryController@detail'
    ]);
});


if (env('APP_DEBUG')) {
    Route::post('api/export/test', function () {
        file_put_contents(storage_path('ttc' . DIRECTORY_SEPARATOR . 'api.log'), var_export(\Request::input(), true));
    });
}
