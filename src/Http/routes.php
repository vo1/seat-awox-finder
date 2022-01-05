<?php

Route::group([
    'namespace' => 'Vo1\Seat\AwoxFinder\Http\Controllers',
    'prefix' => 'awox',
], function () {
    Route::group([
        'middleware' => ['web', 'auth'],
    ], function () {
        Route::get('/find/{name}', [
            'as'   => 'awox.api.find',
            'uses' => 'AwoxApiController@find',
            'middleware' => 'can:awox.read',
        ]);

        Route::get('/', [
            'as'   => 'awox.list',
            'uses' => 'AwoxController@list',
            'middleware' => 'can:awox.read',
        ]);

        Route::get('/item', [
            'as'   => 'awox.form.create',
            'uses' => 'AwoxController@formCreate',
            'middleware' => 'can:awox.create',
        ]);

        Route::post('/item', [
            'as'   => 'awox.create',
            'uses' => 'AwoxController@create',
            'middleware' => 'can:awox.create',
        ]);

        Route::get('/item/{id}', [
            'as'   => 'awox.form.read',
            'uses' => 'AwoxController@formRead',
            'middleware' => 'can:awox.read',
        ]);

        Route::post('/item/{itemId}', [
            'as'   => 'awox.update',
            'uses' => 'AwoxController@update',
            'middleware' => 'can:awox.delete',
        ]);

        Route::get('/item/{id}/delete', [
            'as'   => 'awox.delete',
            'uses' => 'AwoxController@delete',
            'middleware' => 'can:awox.delete',
        ]);
    });
});