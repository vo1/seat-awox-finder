<?php

Route::group([
    'namespace' => 'Vo1\Seat\AwoxFinder\Http\Controllers',
    'prefix' => 'awox',
], function () {
    Route::group([
        'middleware' => ['web', 'auth'],
    ], function () {
        Route::get('/', [
            'as'   => 'awox.list',
            'uses' => 'AwoxController@list',
            'middleware' => 'can:awox.view',
        ]);

        Route::post('/item', [
            'as'   => 'awox.create',
            'uses' => 'AwoxController@view',
            'middleware' => 'can:awox.view',
        ]);

        Route::get('/item/{itemId}', [
            'as'   => 'awox.read',
            'uses' => 'AwoxController@read',
            'middleware' => 'can:awox.add',
        ]);

        Route::post('/item/{itemId}', [
            'as'   => 'awox.update',
            'uses' => 'AwoxController@update',
            'middleware' => 'can:awox.delete',
        ]);

        Route::delete('/item', [
            'as'   => 'awox.delete',
            'uses' => 'AwoxController@delete',
            'middleware' => 'can:awox.delete',
        ]);
    });
});