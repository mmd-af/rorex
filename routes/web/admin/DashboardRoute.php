<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\Dashboard'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DashboardController@index'
            ]);
        });
        Route::group(['prefix' => 'dashboard-ajax', 'as' => 'dashboard.ajax.'], function () {
            Route::post('/checkNewNotification', [
                'as' => 'checkNewNotification',
                'uses' => 'DashboardAjaxController@checkNewNotification'
            ]);
            Route::post('/getNewNotifications', [
                'as' => 'getNewNotifications',
                'uses' => 'DashboardAjaxController@getNewNotifications'
            ]);
        });
    });
});
