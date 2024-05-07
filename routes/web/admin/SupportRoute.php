<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Admin\Support'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'supports', 'as' => 'supports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'SupportController@index'
            ]);
            Route::get('/archived', [
                'as' => 'archived',
                'uses' => 'SupportController@archived'
            ]);
            Route::put('/archiveMessage', [
                'as' => 'archiveMessage',
                'uses' => 'SupportController@archiveMessage'
            ]);
            Route::put('/reArchiveMessage', [
                'as' => 'reArchiveMessage',
                'uses' => 'SupportController@reArchiveMessage'
            ]);
        });
        Route::group(['prefix' => 'supports-ajax', 'as' => 'supports.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'SupportAjaxController@getDataTable'
            ]);
            Route::get('/getArchiveDataTable', [
                'as' => 'getArchiveDataTable',
                'uses' => 'SupportAjaxController@getArchiveDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'SupportAjaxController@show'
            ]);
            Route::post('/archivedShow', [
                'as' => 'archivedShow',
                'uses' => 'SupportAjaxController@archivedShow'
            ]);
        });
    });
});
