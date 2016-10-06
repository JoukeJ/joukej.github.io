<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('/management', ['as' => 'management', 'uses' => 'ManagementController@index']);
    Route::get('/', ['as' => 'home', 'uses' => 'ManagementController@index']);

    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@search']);

    Route::group(['namespace' => 'Management', 'prefix' => 'management'], function () {
        Route::resource('users', 'UserController', ['except' => 'show']);
        Route::resource('roles', 'RoleController', ['except' => 'show']);
        Route::resource('permissions', 'PermissionController', ['except' => 'show']);
    });
});

Route::controller('management/auth', 'Auth\AuthController', [
    'postLogin' => 'auth.login',
    'getLogout' => 'auth.logout'
]);

Route::controller('password', 'Auth\PasswordController', [
    'postEmail' => 'password.email',
    'getReset' => 'password.token',
    'postReset' => 'password.reset',
]);

//Route::get('/confirm_email/{token}', ['uses' => 'UserController@getConfirmEmail', 'as' => 'user.confirm_email']);

Route::controller('mandrill', 'MandrillEventHookController', [
    'postHook' => 'mandrill.hook'
]);
