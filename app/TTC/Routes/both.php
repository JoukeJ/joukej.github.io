<?php

Route::get('/storage/image/{identifier}', [
    'as' => 'storage.image',
    'uses' => 'StorageController@image'
]);
